<?php

namespace App\Services\Contracts;

interface ReservasiServiceInterface
{
    /**
     * Get all reservations
     */
    public function getAllReservasi(): array;

    /**
     * Get reservations by user
     */
    public function getReservasiByUser(int $userId): array;

    /**
     * Get antrean (active queue)
     */
    public function getAntrean(): array;

    /**
     * Find reservasi by ID
     */
    public function findReservasi(int $id): ?array;

    public function findReservasiForUser(int $id, int $userId): ?array;

    /**
     * Create new reservation
     */
    public function createReservasi(array $data): array;

    /**
     * Update reservation
     */
    public function updateReservasi(int $id, array $data): array;

    public function updateReturnForUser(int $id, int $userId, array $data): array;

    /**
     * Update status
     */
    public function updateStatus(int $id, string $status): array;

    /**
     * Cancel reservation
     */
    public function cancelReservasi(int $id, ?int $actorId = null): bool;

    public function cancelForUser(int $id, int $userId): array;

    /**
     * Delete reservation
     */
    public function deleteReservasi(int $id): bool;

    /**
     * Get filtered reservations
     */
    public function getFilteredReservasi(array $filters): array;
}
