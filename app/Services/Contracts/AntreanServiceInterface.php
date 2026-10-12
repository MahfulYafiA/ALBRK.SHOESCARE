<?php

namespace App\Services\Contracts;

interface AntreanServiceInterface
{
    public function updateStatus(int $id, string $status, int $actorId): void;

    public function cancel(int $id, string $reason, int $actorId): void;

    public function updateReservationStatus(int $id, string $status, int $actorId): void;
}
