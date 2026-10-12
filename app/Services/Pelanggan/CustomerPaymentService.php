<?php

namespace App\Services\Pelanggan;

use App\Repositories\Contracts\CustomerPaymentRepositoryInterface;
use App\Services\Contracts\CustomerPaymentServiceInterface;

class CustomerPaymentService implements CustomerPaymentServiceInterface
{
    public function __construct(private CustomerPaymentRepositoryInterface $payments) {}

    public function getIndexData(int $userId, array $filters): array
    {
        return $this->payments->getIndexData($userId, $filters);
    }

    public function getDetailData(int $id, int $userId): array
    {
        return $this->payments->getDetailData($id, $userId);
    }
}
