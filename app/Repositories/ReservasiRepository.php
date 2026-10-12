<?php

namespace App\Repositories;

use App\Models\Layanan;
use App\Models\Reservasi;
use App\Repositories\Contracts\ReservasiRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ReservasiRepository implements ReservasiRepositoryInterface
{
    private const ADMIN_RESERVATION_STATUSES = ['di_terima', 'sedang_diproses', 'selesai', 'diambil', 'dibatalkan'];

    public function getAll(): Collection
    {
        return Reservasi::with(['user', 'detailReservasis'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByUser(int $userId): Collection
    {
        return Reservasi::with(['user', 'detailReservasis.layanan'])
            ->where('id_user', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAntrean(): Collection
    {
        return Reservasi::with(['user', 'detailReservasis'])
            ->whereIn('status', ['menunggu', 'di_terima', 'sedang_diproses', 'selesai'])
            ->orderBy('tanggal_reservasi', 'asc')
            ->get();
    }

    public function getSelesai(): Collection
    {
        return Reservasi::with(['user', 'detailReservasis'])
            ->where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function findById(int $id): ?Reservasi
    {
        return Reservasi::with(['user', 'detailReservasis.layanan'])
            ->find($id);
    }

    public function create(array $data): Reservasi
    {
        return Reservasi::create($data);
    }

    public function update(Reservasi $reservasi, array $data): Reservasi
    {
        $reservasi->update($data);

        return $reservasi->fresh();
    }

    public function updateStatus(int $id, string $status): Reservasi
    {
        $reservasi = $this->findById($id);
        $reservasi->update(['status' => $status]);

        return $reservasi->fresh();
    }

    public function delete(int $id): bool
    {
        $reservasi = $this->findById($id);

        return $reservasi ? $reservasi->delete() : false;
    }

    public function countByStatus(string $status): int
    {
        return Reservasi::where('status', $status)->count();
    }

    public function getFiltered(array $filters): Collection
    {
        $query = Reservasi::with(['user', 'detailReservasis']);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['tanggal_awal'])) {
            $query->whereDate('tanggal_reservasi', '>=', $filters['tanggal_awal']);
        }

        if (! empty($filters['tanggal_akhir'])) {
            $query->whereDate('tanggal_reservasi', '<=', $filters['tanggal_akhir']);
        }

        return $query->orderBy('tanggal_reservasi', 'desc')->get();
    }

    public function getReport(?string $startDate, ?string $endDate, ?string $status): Collection
    {
        $query = Reservasi::with(['user', 'detailReservasis.layanan'])
            ->where('status', '!=', 'dibatalkan');

        if ($status) {
            $query->where('status', $status);
        }

        if ($startDate) {
            $query->whereDate('tanggal_reservasi', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal_reservasi', '<=', $endDate);
        }

        return $query->orderByDesc('tanggal_reservasi')->get();
    }

    public function getTotalOmzet(): int
    {
        return Reservasi::where('status', '!=', 'dibatalkan')
            ->where('status_bayar', 'Lunas')
            ->sum('total_harga');
    }

    public function getMonthlyOmzet(int $year, int $month): int
    {
        return Reservasi::whereYear('tanggal_reservasi', $year)
            ->whereMonth('tanggal_reservasi', $month)
            ->where('status', '!=', 'dibatalkan')
            ->where('status_bayar', 'Lunas')
            ->sum('total_harga');
    }

    public function getAdminQueue(array $filters): LengthAwarePaginator
    {
        $query = Reservasi::query()->with(['user', 'detailReservasis.layanan']);
        $term = trim((string) ($filters['q'] ?? ''));

        if ($term !== '') {
            $number = preg_replace('/^#?RSV[- ]?/i', '', $term);
            $formattedNumber = preg_match('/^(\d{8})[- ](\d+)$/', $number, $matches) === 1
                && checkdate((int) substr($matches[1], 4, 2), (int) substr($matches[1], 6, 2), (int) substr($matches[1], 0, 4))
                ? $matches
                : null;
            $query->where(function (Builder $search) use ($term, $number, $formattedNumber): void {
                $search->whereHas('user', fn (Builder $user) => $user->where('nama', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('no_telp', 'like', "%{$term}%"))
                    ->orWhere('no_hp', 'like', "%{$term}%")
                    ->orWhere('nama_pelanggan', 'like', "%{$term}%");

                if ($formattedNumber) {
                    $search->orWhere(fn (Builder $numberQuery) => $numberQuery
                        ->whereDate('tanggal_reservasi', substr($formattedNumber[1], 0, 4).'-'.substr($formattedNumber[1], 4, 2).'-'.substr($formattedNumber[1], 6, 2))
                        ->where('id_reservasi', (int) $formattedNumber[2]));
                } elseif ($number !== '' && ctype_digit($number)) {
                    $search->orWhere('id_reservasi', 'like', "%{$number}%");
                }
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['id_layanan'])) {
            $query->whereHas('detailReservasis', fn (Builder $details) => $details->where('id_layanan', $filters['id_layanan']));
        }
        if (! empty($filters['metode_layanan'])) {
            $query->where('metode_layanan', $filters['metode_layanan']);
        }
        if (! empty($filters['tanggal_mulai'])) {
            $query->whereDate('tanggal_reservasi', '>=', $filters['tanggal_mulai']);
        }
        if (! empty($filters['tanggal_akhir'])) {
            $query->whereDate('tanggal_reservasi', '<=', $filters['tanggal_akhir']);
        }

        $query = match ($filters['sort'] ?? 'terbaru') {
            'terlama' => $query->orderBy('created_at')->orderBy('id_reservasi'),
            'total_terbesar' => $query->orderByDesc('total_harga')->orderByDesc('created_at'),
            'jadwal_terdekat' => $query->orderBy('tanggal_reservasi')->orderBy('id_reservasi'),
            default => $query->orderByDesc('created_at')->orderByDesc('id_reservasi'),
        };

        return $query->paginate(10)->withQueryString();
    }

    public function getAdminQueueSummary(): array
    {
        $query = Reservasi::query();

        return [
            'total' => (clone $query)->count(),
            'menunggu' => (clone $query)->where('status', 'menunggu')->count(),
            'diproses' => (clone $query)->whereIn('status', ['di_terima', 'sedang_diproses'])->count(),
            'selesai' => (clone $query)->whereIn('status', ['selesai', 'diambil'])->count(),
        ];
    }

    public function getAdminQueueServices(): Collection
    {
        return Layanan::query()->orderBy('nama_layanan')->get(['id_layanan', 'nama_layanan']);
    }

    public function findAdminQueueItem(int $id): Reservasi
    {
        return Reservasi::query()->with(['user', 'detailReservasis.layanan', 'pembayaranRecords', 'pembatalOleh', 'statusDiubahOleh'])->findOrFail($id);
    }

    public function lockAdminQueueItem(int $id): Reservasi
    {
        return Reservasi::query()->with('user')->lockForUpdate()->findOrFail($id);
    }

    public function getAdminReservations(array $filters): LengthAwarePaginator
    {
        $query = Reservasi::query()->with(['user', 'detailReservasis.layanan'])->whereIn('status', self::ADMIN_RESERVATION_STATUSES);
        $term = trim((string) ($filters['q'] ?? ''));

        if ($term !== '') {
            $number = preg_replace('/^#?RSV[- ]?/i', '', $term);
            preg_match('/^(\d{8})[- ](\d+)$/', $number, $numberParts);
            if ($numberParts && ! checkdate((int) substr($numberParts[1], 4, 2), (int) substr($numberParts[1], 6, 2), (int) substr($numberParts[1], 0, 4))) {
                $numberParts = [];
            }
            $query->where(function (Builder $search) use ($term, $number, $numberParts): void {
                $search->where('nama_pelanggan', 'like', "%{$term}%")
                    ->orWhere('no_hp', 'like', "%{$term}%")
                    ->orWhereHas('user', fn (Builder $user) => $user->where('nama', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('no_telp', 'like', "%{$term}%"));
                if ($numberParts) {
                    $search->orWhere(fn (Builder $id) => $id->whereDate('tanggal_reservasi', substr($numberParts[1], 0, 4).'-'.substr($numberParts[1], 4, 2).'-'.substr($numberParts[1], 6, 2))
                        ->where('id_reservasi', (int) $numberParts[2]));
                } elseif ($number !== '' && ctype_digit($number)) {
                    $search->orWhere('id_reservasi', 'like', "%{$number}%");
                }
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['id_layanan'])) {
            $query->whereHas('detailReservasis', fn (Builder $details) => $details->where('id_layanan', $filters['id_layanan']));
        }
        if (! empty($filters['metode_layanan'])) {
            $query->where('metode_layanan', $filters['metode_layanan']);
        }
        if (! empty($filters['tanggal_mulai'])) {
            $query->whereDate('tanggal_reservasi', '>=', $filters['tanggal_mulai']);
        }
        if (! empty($filters['tanggal_akhir'])) {
            $query->whereDate('tanggal_reservasi', '<=', $filters['tanggal_akhir']);
        }

        return $query->orderByDesc('created_at')->orderByDesc('id_reservasi')->paginate(10)->withQueryString();
    }

    public function getAdminReservationSummary(): array
    {
        $orders = Reservasi::query()->whereIn('status', self::ADMIN_RESERVATION_STATUSES);

        return [
            'total' => (clone $orders)->count(),
            'menunggu' => (clone $orders)->where('status', 'di_terima')->count(),
            'diproses' => (clone $orders)->where('status', 'sedang_diproses')->count(),
            'selesai' => (clone $orders)->whereIn('status', ['selesai', 'diambil'])->count(),
        ];
    }

    public function findAdminReservation(int $id): Reservasi
    {
        return Reservasi::query()->whereIn('status', self::ADMIN_RESERVATION_STATUSES)
            ->with(['user', 'statusDiubahOleh', 'detailReservasis.layanan', 'pembayaranRecords'])
            ->findOrFail($id);
    }
}
