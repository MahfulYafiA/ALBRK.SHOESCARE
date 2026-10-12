<?php

namespace App\Repositories;

use App\Models\Pembayaran;
use App\Repositories\Contracts\AdminPaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AdminPaymentRepository implements AdminPaymentRepositoryInterface
{
    public function summary(): array
    {
        $query = Pembayaran::query();

        return [
            'total' => (clone $query)->count(),
            'menunggu' => (clone $query)->where('status', 'menunggu')->count(),
            'berhasil' => (clone $query)->where('status', 'lunas')->count(),
            'ditolak' => (clone $query)->whereIn('status', ['gagal', 'ditolak'])->count(),
            'pendapatan' => (clone $query)->where('status', 'lunas')->sum('jumlah'),
            'bulan_ini' => (clone $query)->where('status', 'lunas')->whereBetween(
                DB::raw('COALESCE(tanggal_bayar, created_at)'), [now()->startOfMonth(), now()->endOfMonth()]
            )->sum('jumlah'),
        ];
    }

    public function search(array $filters): LengthAwarePaginator
    {
        $query = Pembayaran::query()->with(['reservasi.user', 'reservasi.detailReservasis.layanan', 'processedBy']);
        $term = trim((string) ($filters['q'] ?? ''));

        if ($term !== '') {
            $paymentId = preg_match('/^#?PAY[- ]?(\d+)$/i', $term, $matches) ? (int) $matches[1] : null;
            $orderId = preg_match('/^#?RSV[- ]?(\d{8})[- ]?(\d+)$/i', $term, $orderMatches)
                ? (int) $orderMatches[2]
                : (ctype_digit($term) ? (int) $term : null);

            $query->where(function (Builder $search) use ($term, $paymentId, $orderId): void {
                $search->where('id_pembayaran', $paymentId ?? -1)
                    ->orWhereHas('reservasi', function (Builder $reservation) use ($term, $orderId): void {
                        $reservation->where('nama_pelanggan', 'like', "%{$term}%")
                            ->orWhere('no_hp', 'like', "%{$term}%")
                            ->orWhereHas('user', fn (Builder $user) => $user
                                ->where('nama', 'like', "%{$term}%")
                                ->orWhere('email', 'like', "%{$term}%")
                                ->orWhere('no_telp', 'like', "%{$term}%"));
                        if ($orderId !== null) {
                            $reservation->orWhere('id_reservasi', $orderId);
                        }
                    });
            });
        }

        if (! empty($filters['status'])) {
            $query->whereIn('status', match ($filters['status']) {
                'menunggu' => ['menunggu'],
                'berhasil' => ['lunas'],
                'ditolak' => ['gagal', 'ditolak'],
            });
        }
        if (! empty($filters['metode'])) {
            $query->where(fn (Builder $method) => $method
                ->where('metode', $filters['metode'])->orWhere('metode_bayar', $filters['metode']));
        }
        if (! empty($filters['tanggal_mulai'])) {
            $query->whereDate(DB::raw('COALESCE(tanggal_bayar, created_at)'), '>=', $filters['tanggal_mulai']);
        }
        if (! empty($filters['tanggal_akhir'])) {
            $query->whereDate(DB::raw('COALESCE(tanggal_bayar, created_at)'), '<=', $filters['tanggal_akhir']);
        }

        return $query->orderByDesc('id_pembayaran')->paginate(10)->withQueryString();
    }

    public function methods(): array
    {
        return Pembayaran::query()->selectRaw('COALESCE(metode, metode_bayar) as metode_label')
            ->whereNotNull(DB::raw('COALESCE(metode, metode_bayar)'))
            ->distinct()->orderBy('metode_label')->pluck('metode_label')->all();
    }

    public function findWithRelations(int $id): Pembayaran
    {
        return Pembayaran::query()->with(['reservasi.user', 'reservasi.detailReservasis.layanan', 'processedBy'])->findOrFail($id);
    }

    public function lockWithRelations(int $id): Pembayaran
    {
        return Pembayaran::query()->with('reservasi.user')->lockForUpdate()->findOrFail($id);
    }
}
