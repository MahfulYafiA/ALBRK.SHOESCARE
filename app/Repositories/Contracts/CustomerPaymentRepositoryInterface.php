<?php

namespace App\Repositories\Contracts;

interface CustomerPaymentRepositoryInterface
{
    public function getIndexData(int $userId, array $filters): array;

    public function getDetailData(int $id, int $userId): array;
}
