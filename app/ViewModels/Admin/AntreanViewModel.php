<?php

namespace App\ViewModels\Admin;

use App\Models\Reservasi;
use App\Repositories\Contracts\ReservasiRepositoryInterface;

class AntreanViewModel
{
    public function __construct(
        private ReservasiRepositoryInterface $reservasiRepository,
    ) {}

    public function getIndexData(array $filters): array
    {
        return [
            'reservasis' => $this->reservasiRepository->getAdminQueue($filters),
            'stats' => $this->reservasiRepository->getAdminQueueSummary(),
            'layanans' => $this->reservasiRepository->getAdminQueueServices(),
            'filters' => $filters,
            'sort' => $filters['sort'] ?? 'terbaru',
        ];
    }

    public function find(int $id): Reservasi
    {
        return $this->reservasiRepository->findAdminQueueItem($id);
    }
}
