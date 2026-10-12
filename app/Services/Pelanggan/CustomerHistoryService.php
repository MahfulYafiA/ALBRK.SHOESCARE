<?php

namespace App\Services\Pelanggan;

use App\Repositories\Contracts\CustomerHistoryRepositoryInterface;
use App\Services\Contracts\CustomerHistoryServiceInterface;

class CustomerHistoryService implements CustomerHistoryServiceInterface
{
    public function __construct(private CustomerHistoryRepositoryInterface $history) {}

    public function getIndexData(int $userId, array $filters): array
    {
        return $this->history->getIndexData($userId, $filters);
    }

    public function getDetailData(int $id, int $userId): array
    {
        return $this->history->getDetailData($id, $userId);
    }
}
