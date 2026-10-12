<?php

namespace App\Services\Contracts;

use App\Models\Pembayaran;

interface PaymentReviewServiceInterface
{
    public function getIndexData(array $filters): array;

    public function find(int $id): Pembayaran;

    public function verify(int $id, int $actorId): array;

    public function reject(int $id, string $reason, int $actorId): Pembayaran;
}
