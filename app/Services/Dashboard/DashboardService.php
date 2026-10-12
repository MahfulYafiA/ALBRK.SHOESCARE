<?php

namespace App\Services\Dashboard;

use App\Repositories\Contracts\ReservasiRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\DashboardServiceInterface;

class DashboardService implements DashboardServiceInterface
{
    public function __construct(
        private ReservasiRepositoryInterface $reservasiRepository,
        private UserRepositoryInterface $userRepository
    ) {}

    public function getAdminStats(): array
    {
        return [
            'total_antrean' => $this->reservasiRepository->countByStatus('di_terima') +
                              $this->reservasiRepository->countByStatus('sedang_diproses'),
            'total_menunggu' => $this->reservasiRepository->countByStatus('menunggu'),
            'total_diproses' => $this->reservasiRepository->countByStatus('sedang_diproses'),
            'total_selesai' => $this->reservasiRepository->countByStatus('selesai'),
            'total_pelanggan' => $this->userRepository->getTotalPelanggan(),
            'total_omzet' => $this->reservasiRepository->getTotalOmzet(),
        ];
    }

    public function getPelangganStats(int $userId): array
    {
        $reservasis = $this->reservasiRepository->getByUser($userId);

        return [
            'total_reservasi' => $reservasis->count(),
            'menunggu' => $reservasis->where('status', 'menunggu')->count(),
            'diproses' => $reservasis->whereIn('status', ['di_terima', 'sedang_diproses'])->count(),
            'selesai' => $reservasis->where('status', 'selesai')->count(),
            'dibatalkan' => $reservasis->where('status', 'dibatalkan')->count(),
            'total_transaksi' => $reservasis
                ->where('status_bayar', 'Lunas')
                ->where('status', '!=', 'dibatalkan')
                ->sum('total_harga'),
        ];
    }

    public function getMonthlyReport(int $year, int $month): array
    {
        $omzet = $this->reservasiRepository->getMonthlyOmzet($year, $month);

        return [
            'year' => $year,
            'month' => $month,
            'omzet' => $omzet,
            'formatted_omzet' => 'Rp '.number_format($omzet, 0, ',', '.'),
        ];
    }

    public function getYearlyReport(int $year): array
    {
        $monthlyData = [];
        $totalOmzet = 0;

        for ($month = 1; $month <= 12; $month++) {
            $omzet = $this->reservasiRepository->getMonthlyOmzet($year, $month);
            $totalOmzet += $omzet;
            $monthlyData[] = [
                'month' => $month,
                'omzet' => $omzet,
            ];
        }

        return [
            'year' => $year,
            'total_omzet' => $totalOmzet,
            'formatted_omzet' => 'Rp '.number_format($totalOmzet, 0, ',', '.'),
            'monthly' => $monthlyData,
        ];
    }
}
