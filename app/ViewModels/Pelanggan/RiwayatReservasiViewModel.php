<?php

namespace App\ViewModels\Pelanggan;

use App\Services\Contracts\CustomerHistoryServiceInterface;

class RiwayatReservasiViewModel
{
    public function __construct(private CustomerHistoryServiceInterface $historyService) {}

    public function getIndexData(int $userId, array $filters): array
    {
        return $this->historyService->getIndexData($userId, $filters);
    }

    public function getDetailData(int $id, int $userId): array
    {
        return $this->historyService->getDetailData($id, $userId);
    }
}
