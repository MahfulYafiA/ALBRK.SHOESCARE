<?php

namespace App\ViewModels\Pelanggan;

use App\Services\Contracts\CustomerPaymentServiceInterface;

class PembayaranIndexViewModel
{
    public function __construct(private CustomerPaymentServiceInterface $paymentService) {}

    public function getIndexData(int $userId, array $filters): array
    {
        return $this->paymentService->getIndexData($userId, $filters);
    }

    public function getDetailData(int $id, int $userId): array
    {
        return $this->paymentService->getDetailData($id, $userId);
    }
}
