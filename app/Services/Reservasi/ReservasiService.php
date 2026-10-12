<?php

namespace App\Services\Reservasi;

use App\DTOs\ReservasiDTO;
use App\Models\User;
use App\Notifications\CustomerActivityNotification;
use App\Repositories\Contracts\DetailReservasiRepositoryInterface;
use App\Repositories\Contracts\LayananRepositoryInterface;
use App\Repositories\Contracts\PembayaranRepositoryInterface;
use App\Repositories\Contracts\ReservasiRepositoryInterface;
use App\Services\Contracts\ReservasiServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservasiService implements ReservasiServiceInterface
{
    public function __construct(
        private ReservasiRepositoryInterface $reservasiRepository,
        private LayananRepositoryInterface $layananRepository,
        private PembayaranRepositoryInterface $pembayaranRepository,
        private DetailReservasiRepositoryInterface $detailReservasiRepository,
    ) {}

    public function getAllReservasi(): array
    {
        $reservasis = $this->reservasiRepository->getAll();

        return $reservasis->map(fn ($r) => ReservasiDTO::fromModel($r)->toArray())->toArray();
    }

    public function getReservasiByUser(int $userId): array
    {
        $reservasis = $this->reservasiRepository->getByUser($userId);

        return $reservasis->map(fn ($r) => ReservasiDTO::fromModel($r)->toArray())->toArray();
    }

    public function getAntrean(): array
    {
        $reservasis = $this->reservasiRepository->getAntrean();

        return $reservasis->map(fn ($r) => ReservasiDTO::fromModel($r)->toArray())->toArray();
    }

    public function findReservasi(int $id): ?array
    {
        $reservasi = $this->reservasiRepository->findById($id);

        return $reservasi ? ReservasiDTO::fromModel($reservasi)->toArray() : null;
    }

    public function findReservasiForUser(int $id, int $userId): ?array
    {
        $reservasi = $this->findReservasi($id);

        return $reservasi && $reservasi['id_user'] === $userId ? $reservasi : null;
    }

    public function updateReturnForUser(int $id, int $userId, array $data): array
    {
        if (! $this->findReservasiForUser($id, $userId)) {
            return ['success' => false, 'message' => 'Reservasi tidak ditemukan.'];
        }

        return $this->updateReservasi($id, $data);
    }

    public function cancelForUser(int $id, int $userId): array
    {
        $reservasi = $this->findReservasiForUser($id, $userId);
        if (! $reservasi) {
            return ['success' => false, 'message' => 'Reservasi tidak ditemukan.'];
        }
        if (! in_array($reservasi['status'], ['menunggu', 'di_terima'], true)) {
            return ['success' => false, 'message' => 'Reservasi tidak dapat dibatalkan.'];
        }

        return $this->cancelReservasi($id, $userId)
            ? ['success' => true, 'message' => 'Reservasi berhasil dibatalkan.']
            : ['success' => false, 'message' => 'Gagal membatalkan reservasi.'];
    }

    public function createReservasi(array $data): array
    {
        try {
            DB::beginTransaction();

            $idLayanans = array_values(array_unique(array_map('intval', $data['id_layanans'] ?? [])));
            if ($idLayanans === []) {
                throw new \RuntimeException('Pilih minimal satu layanan.');
            }

            $layanans = collect($idLayanans)->map(function (int $idLayanan) {
                $layanan = $this->layananRepository->findById($idLayanan);
                if (! $layanan || $layanan->status !== 'Aktif') {
                    throw new \RuntimeException('Salah satu layanan tidak tersedia.');
                }

                return $layanan;
            });
            $jumlahPerLayanan = [];
            foreach ($layanans as $layanan) {
                $jumlah = (int) ($data['jumlah_layanan'][$layanan->id_layanan] ?? $data['jumlah_layanan'][(string) $layanan->id_layanan] ?? 1);
                if ($jumlah < 1 || $jumlah > 10) {
                    throw new \RuntimeException('Jumlah sepatu harus antara 1 dan 10 pasang per layanan.');
                }
                $jumlahPerLayanan[$layanan->id_layanan] = $jumlah;
            }
            $jumlahSepatu = array_sum($jumlahPerLayanan);
            $totalHarga = $layanans->sum(fn ($layanan) => $layanan->harga * $jumlahPerLayanan[$layanan->id_layanan]);

            // Create reservation
            $reservasi = $this->reservasiRepository->create([
                'id_user' => $data['id_user'],
                'tanggal_reservasi' => $data['tanggal_reservasi'] ?? Carbon::now()->toDateString(),
                'jumlah_sepatu' => $jumlahSepatu,
                'metode_layanan' => $data['metode_layanan'],
                'alamat_jemput' => $data['alamat_jemput'] ?? null,
                'metode_pengembalian' => $data['metode_pengembalian'],
                'status_pengambilan' => $data['metode_pengembalian'] === 'diantar' ? 'diantar' : 'perlu_diambil',
                'status' => 'menunggu',
                'status_bayar' => 'Belum Lunas',
                'metode_bayar' => $data['metode_pembayaran'] === 'Payment Gateway' ? 'Midtrans' : 'Tunai',
                'tanggal_bayar' => null,
                'total_harga' => $totalHarga,
                'wa_pengantaran' => $data['wa_pengantaran'] ?? null,
                'alamat_pengantaran' => $data['alamat_pengantaran'] ?? null,
                'catatan' => $data['catatan'] ?? null,
            ]);

            if ($data['metode_pembayaran'] !== 'Payment Gateway') {
                $this->pembayaranRepository->create([
                    'id_reservasi' => $reservasi->id_reservasi,
                    'jumlah' => $totalHarga,
                    'metode' => strtolower((string) $reservasi->metode_bayar),
                    'metode_bayar' => $reservasi->metode_bayar,
                    'status' => 'menunggu',
                    'tanggal_bayar' => null,
                ]);
            }

            // Store one detail row for each selected service.
            foreach ($layanans as $layanan) {
                $jumlahLayanan = $jumlahPerLayanan[$layanan->id_layanan];
                $this->detailReservasiRepository->create([
                    'id_reservasi' => $reservasi->id_reservasi,
                    'id_layanan' => $layanan->id_layanan,
                    'harga' => $layanan->harga,
                    'jumlah' => $jumlahLayanan,
                    'sub_total' => $layanan->harga * $jumlahLayanan,
                ]);
            }

            DB::commit();

            try {
                $reservasi->user?->notify(new CustomerActivityNotification(
                    'reservasi', 'Reservasi Berhasil Dibuat',
                    'Reservasi #RSV-'.str_pad((string) $reservasi->id_reservasi, 8, '0', STR_PAD_LEFT).' berhasil dibuat dan menunggu konfirmasi.',
                    (int) $reservasi->id_reservasi
                ));
            } catch (\Throwable $notificationError) {
                Log::warning('Reservasi tersimpan, tetapi notifikasi gagal dikirim.', [
                    'id_reservasi' => $reservasi->id_reservasi,
                    'error' => $notificationError->getMessage(),
                ]);
            }

            try {
                $reservationNumber = '#RSV-'.str_pad((string) $reservasi->id_reservasi, 8, '0', STR_PAD_LEFT);
                $customerName = $reservasi->user?->nama ?? 'Pelanggan';
                User::query()
                    ->where('role', 'admin')
                    ->where('status', 'Aktif')
                    ->get()
                    ->each(fn (User $admin): mixed => $admin->notify(new CustomerActivityNotification(
                        'reservasi',
                        'Reservasi Baru',
                        "{$customerName} membuat reservasi {$reservationNumber} dengan total Rp".number_format((int) $reservasi->total_harga, 0, ',', '.').'.',
                        (int) $reservasi->id_reservasi,
                        'Lihat Reservasi',
                    )));
            } catch (\Throwable $notificationError) {
                Log::warning('Reservasi tersimpan, tetapi notifikasi admin gagal dikirim.', [
                    'id_reservasi' => $reservasi->id_reservasi,
                    'error' => $notificationError->getMessage(),
                ]);
            }

            return [
                'success' => true,
                'reservasi' => ReservasiDTO::fromModel($reservasi->fresh())->toArray(),
                'message' => 'Reservasi berhasil dibuat',
            ];
        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            return [
                'success' => false,
                'message' => 'Gagal membuat reservasi: '.$e->getMessage(),
            ];
        }
    }

    public function updateReservasi(int $id, array $data): array
    {
        $reservasi = $this->reservasiRepository->findById($id);

        if (! $reservasi) {
            return [
                'success' => false,
                'message' => 'Reservasi tidak ditemukan',
            ];
        }

        $reservasi = $this->reservasiRepository->update($reservasi, $data);

        return [
            'success' => true,
            'reservasi' => ReservasiDTO::fromModel($reservasi)->toArray(),
            'message' => 'Reservasi berhasil diperbarui',
        ];
    }

    public function updateStatus(int $id, string $status): array
    {
        $reservasi = $this->reservasiRepository->findById($id);

        if (! $reservasi) {
            return [
                'success' => false,
                'message' => 'Reservasi tidak ditemukan',
            ];
        }

        $updateData = ['status' => $status];

        // If taken, update status_pengambilan
        if ($status === 'diambil') {
            $updateData['status_pengambilan'] = 'sudah_diambil';
        }

        $previousStatus = $reservasi->status;
        $reservasi = $this->reservasiRepository->update($reservasi, $updateData);
        $messages = [
            'di_terima' => ['Reservasi Anda Telah Dikonfirmasi', 'Reservasi Anda telah dikonfirmasi.'],
            'sedang_diproses' => ['Reservasi Sedang Diproses', 'Sepatu Anda telah diterima dan sedang dalam proses shoe care.'],
            'selesai' => ['Reservasi Selesai', 'Reservasi Anda telah selesai dan siap untuk diambil atau diantar.'],
        ];
        if ($previousStatus !== $status && isset($messages[$status]) && $reservasi->user) {
            [$title, $message] = $messages[$status];
            $reservasi->user->notify(new CustomerActivityNotification(
                'reservasi', $title,
                $message.' Nomor reservasi #RSV-'.str_pad((string) $reservasi->id_reservasi, 8, '0', STR_PAD_LEFT).'.',
                (int) $reservasi->id_reservasi
            ));
        }

        return [
            'success' => true,
            'reservasi' => ReservasiDTO::fromModel($reservasi)->toArray(),
            'message' => 'Status berhasil diperbarui',
        ];
    }

    public function cancelReservasi(int $id, ?int $actorId = null): bool
    {
        return DB::transaction(function () use ($id, $actorId): bool {
            $reservasi = $this->reservasiRepository->lockAdminQueueItem($id);
            if (! in_array($reservasi->status, ['menunggu', 'di_terima'], true)) {
                return false;
            }

            $reservasi->status = 'dibatalkan';
            $reservasi->dibatalkan_oleh = $actorId;
            $reservasi->status_diubah_oleh = $actorId;
            $reservasi->save();

            return true;
        });
    }

    public function deleteReservasi(int $id): bool
    {
        return $this->reservasiRepository->delete($id);
    }

    public function getFilteredReservasi(array $filters): array
    {
        $reservasis = $this->reservasiRepository->getFiltered($filters);

        return $reservasis->map(fn ($r) => ReservasiDTO::fromModel($r)->toArray())->toArray();
    }
}
