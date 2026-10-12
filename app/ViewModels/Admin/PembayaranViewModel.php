<?php

namespace App\ViewModels\Admin;

use App\Models\Pembayaran;
use App\Services\Contracts\PaymentReviewServiceInterface;

class PembayaranViewModel
{
    public function __construct(private PaymentReviewServiceInterface $paymentReviewService) {}

    public function getIndexData(array $filters): array
    {
        return $this->paymentReviewService->getIndexData($filters);
    }

    public function find(int $id): Pembayaran
    {
        return $this->paymentReviewService->find($id);
    }
}
