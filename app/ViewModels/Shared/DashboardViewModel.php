<?php

namespace App\ViewModels\Shared;

use App\Services\Contracts\DashboardServiceInterface;
use App\Services\Contracts\ReservasiServiceInterface;
use Carbon\Carbon;

class DashboardViewModel
{
    public function __construct(
        private DashboardServiceInterface $dashboardService,
        private ReservasiServiceInterface $reservasiService
    ) {}

    /**
     * Get dashboard data for admin
     */
    public function getDashboardData(): array
    {
        $reservations = $this->reservasiService->getAllReservasi();
        $recent = array_slice($reservations, 0, 5);
        $daily = collect($reservations)
            ->filter(fn (array $reservation) => ($reservation['tanggal_reservasi'] ?? null) !== null)
            ->groupBy(fn (array $reservation) => Carbon::parse($reservation['tanggal_reservasi'])->format('Y-m-d'));
        $weeklyActivity = collect(range(6, 0))
            ->map(function (int $daysAgo) use ($daily) {
                $date = now()->subDays($daysAgo);

                return [
                    'label' => $date->translatedFormat('D'),
                    'count' => $daily->get($date->format('Y-m-d'), collect())->count(),
                ];
            })->values()->all();

        return [
            'stats' => $this->dashboardService->getAdminStats(),
            'latestReservations' => $recent,
            'weeklyActivity' => $weeklyActivity,
        ];
    }
}
