<?php

namespace App\Repositories;

use App\Models\Layanan;
use App\Models\Reservasi;
use App\Repositories\Contracts\CustomerHistoryRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class CustomerHistoryRepository implements CustomerHistoryRepositoryInterface
{
    private const HISTORY_STATUSES = ['selesai', 'dibatalkan'];

    public function getIndexData(int $userId, array $filters): array
    {

        $ownedHistory = fn (): Builder => Reservasi::query()
            ->where('id_user', $userId)
            ->whereIn('status', self::HISTORY_STATUSES);

        $summary = $ownedHistory()
            ->selectRaw("COUNT(*) as total_riwayat, SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as reservasi_selesai, SUM(CASE WHEN status = 'dibatalkan' THEN 1 ELSE 0 END) as reservasi_dibatalkan, SUM(CASE WHEN status = 'selesai' THEN total_harga ELSE 0 END) as total_pengeluaran")
            ->first();

        $query = $ownedHistory()->with(['detailReservasis.layanan']);

        if (! empty($filters['q'])) {
            $term = trim($filters['q']);
            $formattedReservation = preg_match('/^#?RSV[- ]?(\d{8})[- ]?(\d+)$/i', $term, $matches) === 1
                && checkdate((int) substr($matches[1], 4, 2), (int) substr($matches[1], 6, 2), (int) substr($matches[1], 0, 4))
                ? $matches
                : null;
            $reservationNumber = preg_replace('/^#?RSV[- ]?/i', '', $term);
            $reservationNumber = is_string($reservationNumber) && ctype_digit($reservationNumber) ? (ltrim($reservationNumber, '0') ?: '0') : $reservationNumber;

            $query->where(function (Builder $search) use ($term, $reservationNumber, $formattedReservation): void {
                $search->whereHas('detailReservasis.layanan', fn (Builder $service) => $service->where('nama_layanan', 'like', "%{$term}%"));

                if ($formattedReservation) {
                    $search->orWhere(function (Builder $number) use ($formattedReservation): void {
                        $number->whereDate('tanggal_reservasi', substr($formattedReservation[1], 0, 4).'-'.substr($formattedReservation[1], 4, 2).'-'.substr($formattedReservation[1], 6, 2))
                            ->where('id_reservasi', (int) $formattedReservation[2]);
                    });
                }

                if ($reservationNumber !== '' && preg_match('/^[0-9]+$/', $reservationNumber)) {
                    $search->orWhere('id_reservasi', 'like', "%{$reservationNumber}%");
                }
            });
        }

        if (! empty($filters['tanggal_mulai'])) {
            $query->whereDate('tanggal_reservasi', '>=', $filters['tanggal_mulai']);
        }
        if (! empty($filters['tanggal_selesai'])) {
            $query->whereDate('tanggal_reservasi', '<=', $filters['tanggal_selesai']);
        }
        if (! empty($filters['id_layanan'])) {
            $query->whereHas('detailReservasis', fn (Builder $details) => $details->where('id_layanan', $filters['id_layanan']));
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $riwayat = $query->orderByDesc('tanggal_reservasi')->orderByDesc('id_reservasi')
            ->paginate(10)
            ->withQueryString();

        $layanans = Layanan::query()->orderBy('nama_layanan')->get(['id_layanan', 'nama_layanan']);

        return compact('riwayat', 'layanans', 'summary', 'filters');
    }

    public function getDetailData(int $id, int $userId): array
    {
        $reservasi = Reservasi::query()
            ->where('id_user', $userId)
            ->whereIn('status', self::HISTORY_STATUSES)
            ->with(['detailReservasis.layanan'])
            ->findOrFail($id);

        return ['reservasi' => $reservasi];
    }
}
