<?php

namespace App\Repositories;

use App\Models\Reservasi;
use App\Repositories\Contracts\CustomerPaymentRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomerPaymentRepository implements CustomerPaymentRepositoryInterface
{
    public function getIndexData(int $userId, array $filters): array
    {
        $owned = fn (): Builder => Reservasi::query()->where('id_user', $userId);

        $summary = $owned()->selectRaw("COUNT(*) as total, SUM(CASE WHEN LOWER(status_bayar) IN ('belum lunas', 'menunggu') THEN 1 ELSE 0 END) as menunggu, SUM(CASE WHEN LOWER(status_bayar) = 'menunggu verifikasi' THEN 1 ELSE 0 END) as verifikasi, SUM(CASE WHEN LOWER(status_bayar) = 'lunas' THEN 1 ELSE 0 END) as berhasil, SUM(CASE WHEN LOWER(status_bayar) = 'lunas' THEN total_harga ELSE 0 END) as pengeluaran")->first();

        $query = $owned()->with('detailReservasis.layanan');
        if (! empty($filters['q'])) {
            $term = trim($filters['q']);
            preg_match('/^#?RSV[- ]?(\d{8})[- ]?(\d+)$/i', $term, $orderNumber);
            if ($orderNumber && ! checkdate((int) substr($orderNumber[1], 4, 2), (int) substr($orderNumber[1], 6, 2), (int) substr($orderNumber[1], 0, 4))) {
                $orderNumber = [];
            }
            preg_match('/^#?PAY[- ]?(\d+)$/i', $term, $paymentNumber);
            $query->where(function (Builder $search) use ($term, $orderNumber, $paymentNumber): void {
                $search->where('id_reservasi', 'like', "%{$term}%")
                    ->orWhereHas('detailReservasis.layanan', fn (Builder $service) => $service->where('nama_layanan', 'like', "%{$term}%"));
                if ($orderNumber) {
                    $search->orWhere(fn (Builder $number) => $number->whereDate('tanggal_reservasi', substr($orderNumber[1], 0, 4).'-'.substr($orderNumber[1], 4, 2).'-'.substr($orderNumber[1], 6, 2))->where('id_reservasi', (int) $orderNumber[2]));
                }
                if ($paymentNumber && Schema::hasTable('tr_pembayaran')) {
                    $search->orWhereIn('id_reservasi', DB::table('tr_pembayaran')->select('id_reservasi')->where('id_pembayaran', (int) $paymentNumber[1]));
                }
            });
        }
        if (! empty($filters['tanggal_mulai'])) {
            $query->whereDate('tanggal_reservasi', '>=', $filters['tanggal_mulai']);
        }
        if (! empty($filters['tanggal_selesai'])) {
            $query->whereDate('tanggal_reservasi', '<=', $filters['tanggal_selesai']);
        }
        if (! empty($filters['metode'])) {
            $query->where('metode_bayar', $filters['metode']);
        }
        if (! empty($filters['status'])) {
            $statuses = match ($filters['status']) {
                'menunggu' => ['Belum Lunas', 'Menunggu'],
                'verifikasi' => ['Menunggu Verifikasi'],
                'berhasil' => ['Lunas'],
                'ditolak' => ['Ditolak', 'Gagal'],
                'dibatalkan' => ['Dibatalkan'],
            };
            if ($filters['status'] === 'ditolak') {
                if (Schema::hasTable('tr_pembayaran') && Schema::hasColumn('tr_pembayaran', 'status')) {
                    $latestRejectedPayments = DB::table('tr_pembayaran as current_payment')
                        ->select('current_payment.id_reservasi')
                        ->whereIn('current_payment.status', ['gagal', 'ditolak'])
                        ->whereRaw('current_payment.id_pembayaran = (SELECT MAX(previous_payment.id_pembayaran) FROM tr_pembayaran as previous_payment WHERE previous_payment.id_reservasi = current_payment.id_reservasi)');
                    $query->whereIn('id_reservasi', $latestRejectedPayments);
                } else {
                    $query->whereIn('status_bayar', $statuses);
                }
            } elseif ($filters['status'] === 'dibatalkan') {
                $query->where(fn (Builder $statusQuery) => $statusQuery->whereIn('status_bayar', $statuses)->orWhere('status', 'dibatalkan'));
            } else {
                $query->whereIn('status_bayar', $statuses);
            }
        }

        $payments = $query->orderByDesc('tanggal_reservasi')->orderByDesc('id_reservasi')->paginate(10)->withQueryString();
        $activePayments = $owned()->whereIn('status_bayar', ['Belum Lunas', 'Menunggu', 'Ditolak'])
            ->where('status', '!=', 'dibatalkan')->with('detailReservasis.layanan')
            ->orderByDesc('tanggal_reservasi')->limit(3)->get();
        $paymentIds = $payments->getCollection()->pluck('id_reservasi')->merge($activePayments->pluck('id_reservasi'))->unique();
        $paymentRows = Schema::hasTable('tr_pembayaran')
            ? DB::table('tr_pembayaran')->whereIn('id_reservasi', $paymentIds)->orderByDesc('id_pembayaran')->get()->unique('id_reservasi')->keyBy('id_reservasi')
            : collect();
        $methods = $owned()->whereNotNull('metode_bayar')->distinct()->orderBy('metode_bayar')->pluck('metode_bayar');

        return compact('payments', 'activePayments', 'paymentRows', 'methods', 'summary', 'filters');
    }

    public function getDetailData(int $id, int $userId): array
    {
        $reservasi = Reservasi::query()->where('id_user', $userId)
            ->with('detailReservasis.layanan')->findOrFail($id);
        $payment = Schema::hasTable('tr_pembayaran')
            ? DB::table('tr_pembayaran')->where('id_reservasi', $reservasi->id_reservasi)->orderByDesc('id_pembayaran')->first()
            : null;

        return compact('reservasi', 'payment');
    }
}
