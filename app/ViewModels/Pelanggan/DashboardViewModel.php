<?php

namespace App\ViewModels\Pelanggan;

use App\Services\Contracts\DashboardServiceInterface;
use App\Services\Contracts\LayananServiceInterface;
use App\Services\Contracts\ReservasiServiceInterface;

class DashboardViewModel
{
    public function __construct(
        private DashboardServiceInterface $dashboardService,
        private ReservasiServiceInterface $reservasiService,
        private LayananServiceInterface $layananService
    ) {}

    /**
     * Get dashboard data for pelanggan
     */
    public function getDashboardData(int $userId): array
    {
        $stats = $this->dashboardService->getPelangganStats($userId);

        return [
            'stats' => $stats,
            'latestReservations' => array_slice($this->reservasiService->getReservasiByUser($userId), 0, 5),
            'popularServices' => array_slice($this->layananService->getActiveLayanan(), 0, 5),
        ];
    }
}
