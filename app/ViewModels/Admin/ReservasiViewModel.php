<?php

namespace App\ViewModels\Admin;

use App\Repositories\Contracts\ReservasiRepositoryInterface;

class ReservasiViewModel
{
    private const SUCCESSFUL_PAYMENT_STATUSES = ['lunas', 'settlement', 'capture', 'berhasil'];

    public function __construct(
        private ReservasiRepositoryInterface $reservasiRepository,
    ) {}

    public function getIndexData(array $filters): array
    {
        return [
            'reservasis' => $this->reservasiRepository->getAdminReservations($filters),
            'stats' => $this->reservasiRepository->getAdminReservationSummary(),
            'layanans' => $this->reservasiRepository->getAdminQueueServices(),
            'filters' => $filters,
        ];
    }

    public function getDetailData(int $id): array
    {
        $reservasi = $this->reservasiRepository->findAdminReservation($id);
        $paid = $reservasi->pembayaranRecords
            ->filter(fn ($payment) => in_array(strtolower((string) $payment->status), self::SUCCESSFUL_PAYMENT_STATUSES, true))
            ->sum(fn ($payment) => (int) $payment->jumlah);

        if ($paid === 0 && strtolower((string) $reservasi->status_bayar) === 'lunas') {
            $paid = (int) $reservasi->total_harga;
        }

        return ['reservasi' => $reservasi, 'paid' => min($paid, (int) $reservasi->total_harga)];
    }
}
