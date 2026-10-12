<?php

namespace App\Services\Payment;

use App\DTOs\MidtransParamsDTO;
use App\Models\Pembayaran;
use App\Notifications\CustomerActivityNotification;
use App\Repositories\Contracts\PembayaranRepositoryInterface;
use App\Repositories\Contracts\ReservasiRepositoryInterface;
use App\Services\Contracts\MidtransServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService implements MidtransServiceInterface
{
    public function __construct(
        private PembayaranRepositoryInterface $pembayaranRepository,
        private ReservasiRepositoryInterface $reservasiRepository
    ) {
        // Configure Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function getSnapToken(int $reservasiId): ?string
    {
        try {
            $reservasi = $this->reservasiRepository->findById($reservasiId);

            if (! $reservasi
                || strtolower((string) $reservasi->metode_bayar) !== 'midtrans'
                || strtolower((string) $reservasi->status_bayar) === 'lunas'
                || $reservasi->status === 'dibatalkan') {
                return null;
            }

            $orderId = 'RSV-'.$reservasi->id_reservasi.'-'.time();

            $params = new MidtransParamsDTO(
                orderId: $orderId,
                grossAmount: $reservasi->total_harga,
                customerName: $reservasi->user->nama ?? 'Pelanggan',
                customerEmail: $reservasi->user->email ?? 'pelanggan@albrk.com',
                phone: $reservasi->user->no_telp,
            );

            // Update pembayaran with order ID
            $pembayaran = $this->pembayaranRepository->getByReservasi($reservasiId);
            $isNewPayment = ! $pembayaran || in_array($pembayaran->status, ['gagal', 'ditolak', 'dikembalikan'], true);
            if ($isNewPayment) {
                $pembayaran = $this->pembayaranRepository->create([
                    'id_reservasi' => $reservasiId,
                    'jumlah' => $reservasi->total_harga,
                    'metode' => 'midtrans',
                    'metode_bayar' => 'Midtrans',
                    'status' => 'menunggu',
                ]);
                $reservasi->update(['status_bayar' => 'Belum Lunas', 'tanggal_bayar' => null, 'metode_bayar' => 'Midtrans']);
            }
            $pembayaran = $this->pembayaranRepository->update($pembayaran, ['midtrans_order_id' => $orderId]);

            $snapToken = Snap::getSnapToken($params->toMidtransParams());

            // Store snap token
            if ($pembayaran) {
                $this->pembayaranRepository->update($pembayaran, [
                    'snap_token' => $snapToken,
                ]);
            }

            if ($snapToken && $isNewPayment && $reservasi->user) {
                $reservasi->user->notify(new CustomerActivityNotification(
                    'pembayaran', 'Pembayaran Menunggu Verifikasi',
                    'Instruksi pembayaran untuk reservasi #RSV-'.str_pad((string) $reservasi->id_reservasi, 8, '0', STR_PAD_LEFT).' telah dibuat dan menunggu pembayaran.',
                    (int) $reservasi->id_reservasi
                ));
            }

            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Midtrans Error: '.$e->getMessage());

            return null;
        }
    }

    public function processCallback(array $notification): array
    {
        try {
            $orderId = $notification['order_id'];
            $transactionStatus = $notification['transaction_status'];
            $pembayaran = $this->pembayaranRepository->findByOrderId($orderId);

            if (! $pembayaran) {
                return [
                    'success' => false,
                    'message' => 'Pembayaran tidak ditemukan',
                ];
            }

            if (isset($notification['gross_amount']) && (int) round((float) $notification['gross_amount']) !== (int) $pembayaran->jumlah) {
                return ['success' => false, 'message' => 'Jumlah pembayaran tidak sesuai'];
            }

            $status = match ($transactionStatus) {
                'settlement' => 'lunas',
                'capture' => ($notification['fraud_status'] ?? null) === 'accept' ? 'lunas' : 'menunggu',
                'pending' => 'menunggu',
                'deny', 'expire', 'cancel' => 'gagal',
                default => $pembayaran->status,
            };

            // A late pending/failed notification must not reverse a confirmed payment.
            if ($pembayaran->status === 'lunas' && $status !== 'lunas') {
                return ['success' => true, 'message' => 'Pembayaran sudah terkonfirmasi', 'status' => 'lunas'];
            }

            $previousPaymentStatus = $pembayaran->status;
            $updateData = [
                'status' => $status,
                'midtrans_transaction_id' => $notification['transaction_id'] ?? null,
            ];

            if ($status === 'lunas') {
                $updateData['tanggal_bayar'] = Carbon::now();
                if (in_array($previousPaymentStatus, ['gagal', 'ditolak'], true)) {
                    $updateData['diproses_oleh'] = null;
                    $updateData['diproses_pada'] = null;
                    $updateData['alasan_penolakan'] = null;
                }
            }

            $this->pembayaranRepository->update($pembayaran, $updateData);

            // The signed gateway callback is the authority for payment completion.
            $reservasi = $this->reservasiRepository->findById((int) $pembayaran->id_reservasi);
            if ($reservasi) {
                $reservasi->update([
                    'status_bayar' => $status === 'lunas' ? 'Lunas' : 'Belum Lunas',
                    'tanggal_bayar' => $status === 'lunas' ? Carbon::now() : null,
                    'metode_bayar' => 'Midtrans',
                ]);
                if ($previousPaymentStatus !== $status && $reservasi->user) {
                    $reference = '#RSV-'.str_pad((string) $reservasi->id_reservasi, 8, '0', STR_PAD_LEFT);
                    if ($status === 'lunas') {
                        $reservasi->user->notify(new CustomerActivityNotification('pembayaran', 'Pembayaran Berhasil', 'Pembayaran sebesar Rp'.number_format((int) $pembayaran->jumlah, 0, ',', '.').' untuk reservasi '.$reference.' telah berhasil diverifikasi.', (int) $reservasi->id_reservasi));
                    } elseif ($status === 'gagal') {
                        $reservasi->user->notify(new CustomerActivityNotification('pembayaran', 'Pembayaran Ditolak', 'Pembayaran untuk reservasi '.$reference.' gagal diproses. Silakan periksa kembali pembayaran Anda.', (int) $reservasi->id_reservasi));
                    } elseif ($status === 'menunggu' && $previousPaymentStatus !== 'menunggu') {
                        $reservasi->user->notify(new CustomerActivityNotification('pembayaran', 'Pembayaran Menunggu Verifikasi', 'Pembayaran untuk reservasi '.$reference.' sedang menunggu verifikasi.', (int) $reservasi->id_reservasi));
                    }
                }
            }

            return [
                'success' => true,
                'message' => 'Callback berhasil diproses',
                'status' => $status,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: '.$e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function hasValidCallbackSignature(array $notification): bool
    {
        $serverKey = (string) config('services.midtrans.server_key');
        if ($serverKey === '') {
            return false;
        }

        $expectedSignature = hash(
            'sha512',
            $notification['order_id'].$notification['status_code'].$notification['gross_amount'].$serverKey,
        );

        return hash_equals($expectedSignature, $notification['signature_key']);
    }

    public function checkTransaction(string $orderId): ?array
    {
        try {
            $status = Transaction::status($orderId);

            return (array) $status;
        } catch (\Exception $e) {
            Log::error('Midtrans Check Transaction Error: '.$e->getMessage());

            return null;
        }
    }
}
