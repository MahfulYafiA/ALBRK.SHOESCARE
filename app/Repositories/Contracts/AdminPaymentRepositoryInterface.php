<?php

namespace App\Repositories\Contracts;

use App\Models\Pembayaran;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AdminPaymentRepositoryInterface
{
    public function summary(): array;

    public function search(array $filters): LengthAwarePaginator;

    public function methods(): array;

    public function findWithRelations(int $id): Pembayaran;

    public function lockWithRelations(int $id): Pembayaran;
}
