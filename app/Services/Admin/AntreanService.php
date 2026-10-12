<?php

namespace App\Services\Admin;

use App\Models\Reservasi;
use App\Notifications\CustomerActivityNotification;
use App\Repositories\Contracts\ReservasiRepositoryInterface;
use App\Services\Contracts\AntreanServiceInterface;
use Illuminate\Support\Facades\DB;

class AntreanService implements AntreanServiceInterface
{
    public function __construct(private ReservasiRepositoryInterface $reservasiRepository) {}

    public function updateStatus(int $id, string $status, int $actorId): void
    {
        $transitions = ['menunggu' => 'di_terima', 'di_terima' => 'sedang_diproses', 'sedang_diproses' => 'selesai', 'selesai' => 'diambil'];

        DB::transaction(function () use ($id, $status, $actorId, $transitions): void {
            $reservasi = $this->reservasiRepository->lockAdminQueueItem($id);
            abort_unless(($transitions[$reservasi->status] ?? null) === $status, 422, 'Transisi status reservasi tidak valid.');
            abort_unless($status !== 'diambil' || $reservasi->metode_pengembalian === 'diambil', 422, 'Reservasi dengan pengantaran tidak dapat ditandai sudah diambil di toko.');

            $reservasi->status = $status;
            $reservasi->status_diubah_oleh = $actorId;
            if ($status === 'diambil') {
                $reservasi->status_pengambilan = 'sudah_diambil';
            }
            $reservasi->save();

            if ($reservasi->user) {
                [$title, $phrase] = [
                    'di_terima' => ['Reservasi Dikonfirmasi', 'telah dikonfirmasi.'],
                    'sedang_diproses' => ['Reservasi Sedang Diproses', 'sedang diproses.'],
                    'selesai' => ['Reservasi Selesai', 'telah selesai dan siap diambil atau diantar.'],
                    'diambil' => ['Sepatu Telah Diambil', 'sepatu telah diambil di toko.'],
                ][$status];
                $reservasi->user->notify(new CustomerActivityNotification(
                    'reservasi', $title,
                    'Reservasi #'.$this->reservationNumber($reservasi).' '.$phrase,
                    (int) $reservasi->id_reservasi,
                ));
            }
        });
    }

    public function cancel(int $id, string $reason, int $actorId): void
    {
        DB::transaction(function () use ($id, $reason, $actorId): void {
            $reservasi = $this->reservasiRepository->lockAdminQueueItem($id);
            abort_unless(in_array($reservasi->status, ['menunggu', 'di_terima'], true), 422, 'Reservasi tidak dapat dibatalkan pada status ini.');

            $reservasi->status = 'dibatalkan';
            $reservasi->alasan_pembatalan = $reason;
            $reservasi->dibatalkan_oleh = $actorId;
            $reservasi->status_diubah_oleh = $actorId;
            $reservasi->save();
            $reservasi->user?->notify(new CustomerActivityNotification(
                'reservasi', 'Reservasi Dibatalkan',
                'Reservasi #'.$this->reservationNumber($reservasi).' telah dibatalkan. Alasan: '.$reason,
                (int) $reservasi->id_reservasi,
            ));
        });
    }

    public function updateReservationStatus(int $id, string $status, int $actorId): void
    {
        $transitions = ['di_terima' => 'sedang_diproses', 'sedang_diproses' => 'selesai'];

        DB::transaction(function () use ($id, $status, $actorId, $transitions): void {
            $reservasi = $this->reservasiRepository->lockAdminQueueItem($id);
            abort_unless(($transitions[$reservasi->status] ?? null) === $status, 422, 'Status reservasi hanya dapat diubah sesuai urutan proses.');

            $reservasi->status = $status;
            $reservasi->status_diubah_oleh = $actorId;
            $reservasi->save();

            if ($reservasi->user) {
                $number = $this->reservationNumber($reservasi);
                [$title, $message] = $status === 'selesai'
                    ? ['Reservasi Selesai', "Reservasi #{$number} telah selesai dan siap diambil atau dikirim."]
                    : ['Reservasi Sedang Diproses', "Reservasi #{$number} sedang diproses."];
                $reservasi->user->notify(new CustomerActivityNotification('reservasi', $title, $message, (int) $reservasi->id_reservasi));
            }
        });
    }

    private function reservationNumber(Reservasi $reservasi): string
    {
        return ($reservasi->tanggal_reservasi?->format('Ymd') ?? now()->format('Ymd')).'-'.str_pad((string) $reservasi->id_reservasi, 3, '0', STR_PAD_LEFT);
    }
}
