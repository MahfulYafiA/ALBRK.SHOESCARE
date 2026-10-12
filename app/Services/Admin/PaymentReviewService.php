<?php

namespace App\Services\Admin;

use App\Models\Pembayaran;
use App\Notifications\CustomerActivityNotification;
use App\Repositories\Contracts\AdminPaymentRepositoryInterface;
use App\Services\Contracts\MidtransServiceInterface;
use App\Services\Contracts\PaymentReviewServiceInterface;
use Illuminate\Support\Facades\DB;

class PaymentReviewService implements PaymentReviewServiceInterface
{
    public function __construct(
        private AdminPaymentRepositoryInterface $payments,
        private MidtransServiceInterface $midtrans,
    ) {}

    public function getIndexData(array $filters): array
    {
        return [
            'payments' => $this->payments->search($filters),
            'summary' => $this->payments->summary(),
            'filters' => $filters,
            'methods' => $this->payments->methods(),
        ];
    }

    public function find(int $id): Pembayaran
    {
        return $this->payments->findWithRelations($id);
    }

    public function verify(int $id, int $actorId): array
    {
        $candidate = $this->payments->findWithRelations($id);
        if (strtolower((string) ($candidate->metode ?: $candidate->metode_bayar)) === 'midtrans') {
            abort_unless($candidate->midtrans_order_id, 422, 'Referensi pembayaran Midtrans tidak tersedia.');
            $gateway = $this->midtrans->checkTransaction($candidate->midtrans_order_id);
            if (! $this->isGatewayPaid($gateway)) {
                $status = strtolower((string) data_get($gateway, 'transaction_status')) ?: 'tidak tersedia';

                return [
                    'payment' => null,
                    'error' => 'Midtrans belum mengonfirmasi pembayaran berhasil. Status gateway saat ini: '.$status.'.',
                ];
            }
        }

        $payment = DB::transaction(function () use ($id, $actorId): Pembayaran {
            $payment = $this->payments->lockWithRelations($id);
            abort_unless($payment->status === 'menunggu', 422, 'Hanya pembayaran yang menunggu yang dapat diverifikasi.');
            abort_unless((int) $payment->jumlah > 0, 422, 'Nominal pembayaran tidak valid.');

            $now = now();
            $payment->forceFill([
                'status' => 'lunas',
                'tanggal_bayar' => $now,
                'diproses_oleh' => $actorId,
                'diproses_pada' => $now,
                'alasan_penolakan' => null,
            ])->save();
            $payment->reservasi?->update([
                'status_bayar' => 'Lunas',
                'tanggal_bayar' => $now,
                'metode_bayar' => $payment->metode_bayar ?: $payment->metode,
            ]);
            $payment->reservasi?->user?->notify(new CustomerActivityNotification(
                'pembayaran', 'Pembayaran Berhasil',
                'Pembayaran sebesar Rp'.number_format((int) $payment->jumlah, 0, ',', '.').' untuk reservasi #RSV-'.str_pad((string) $payment->id_reservasi, 8, '0', STR_PAD_LEFT).' telah berhasil diverifikasi oleh Admin.',
                (int) $payment->id_reservasi, 'Lihat Pembayaran',
            ));

            return $payment;
        });

        return ['payment' => $payment, 'error' => null];
    }

    public function reject(int $id, string $reason, int $actorId): Pembayaran
    {
        $candidate = $this->payments->findWithRelations($id);
        if (strtolower((string) ($candidate->metode ?: $candidate->metode_bayar)) === 'midtrans' && $candidate->midtrans_order_id) {
            abort_unless(! $this->isGatewayPaid($this->midtrans->checkTransaction($candidate->midtrans_order_id)), 422,
                'Midtrans sudah mengonfirmasi pembayaran berhasil. Pembayaran ini tidak dapat ditolak.');
        }

        return DB::transaction(function () use ($id, $reason, $actorId): Pembayaran {
            $payment = $this->payments->lockWithRelations($id);
            abort_unless($payment->status === 'menunggu', 422, 'Hanya pembayaran yang menunggu yang dapat ditolak.');

            $payment->forceFill([
                'status' => 'gagal',
                'alasan_penolakan' => $reason,
                'diproses_oleh' => $actorId,
                'diproses_pada' => now(),
            ])->save();
            $payment->reservasi?->update(['status_bayar' => 'Ditolak', 'tanggal_bayar' => null]);
            $payment->reservasi?->user?->notify(new CustomerActivityNotification(
                'pembayaran', 'Pembayaran Ditolak',
                'Pembayaran untuk reservasi #RSV-'.str_pad((string) $payment->id_reservasi, 8, '0', STR_PAD_LEFT).' ditolak. Alasan: '.$reason,
                (int) $payment->id_reservasi, 'Lihat Pembayaran',
            ));

            return $payment;
        });
    }

    private function isGatewayPaid(?array $gateway): bool
    {
        $status = strtolower((string) data_get($gateway, 'transaction_status'));

        return $status === 'settlement'
            || ($status === 'capture' && strtolower((string) data_get($gateway, 'fraud_status')) === 'accept');
    }
}
