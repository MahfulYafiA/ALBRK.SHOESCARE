<?php

namespace App\Repositories\Contracts;

interface CustomerHistoryRepositoryInterface
{
    public function getIndexData(int $userId, array $filters): array;

    public function getDetailData(int $id, int $userId): array;
}
