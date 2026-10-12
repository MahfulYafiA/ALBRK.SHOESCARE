<?php

namespace App\Services\Contracts;

interface CustomerPaymentServiceInterface
{
    public function getIndexData(int $userId, array $filters): array;

    public function getDetailData(int $id, int $userId): array;
}
