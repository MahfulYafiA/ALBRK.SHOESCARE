<?php

namespace App\Repositories\Contracts;

use App\Models\Reservasi;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ReservasiRepositoryInterface
{
    /**
     * Get all reservations
     */
    public function getAll(): Collection;

    /**
     * Get reservations by user
     */
    public function getByUser(int $userId): Collection;

    /**
     * Get active reservations (queue)
     */
    public function getAntrean(): Collection;

    /**
     * Get completed reservations
     */
    public function getSelesai(): Collection;

    /**
     * Find reservation by ID
     */
    public function findById(int $id): ?Reservasi;

    /**
     * Create new reservation
     */
    public function create(array $data): Reservasi;

    /**
     * Update reservation
     */
    public function update(Reservasi $reservasi, array $data): Reservasi;

    /**
     * Update status
     */
    public function updateStatus(int $id, string $status): Reservasi;

    /**
     * Delete reservation
     */
    public function delete(int $id): bool;

    /**
     * Count by status
     */
    public function countByStatus(string $status): int;

    /**
     * Get filtered reservations
     */
    public function getFiltered(array $filters): Collection;

    /** Get non-cancelled reservations for a report, with optional filters. */
    public function getReport(?string $startDate, ?string $endDate, ?string $status): Collection;

    /**
     * Calculate total omzet
     */
    public function getTotalOmzet(): int;

    /**
     * Get monthly omzet
     */
    public function getMonthlyOmzet(int $year, int $month): int;

    public function getAdminQueue(array $filters): LengthAwarePaginator;

    public function getAdminQueueSummary(): array;

    public function getAdminQueueServices(): Collection;

    public function findAdminQueueItem(int $id): Reservasi;

    public function lockAdminQueueItem(int $id): Reservasi;

    public function getAdminReservations(array $filters): LengthAwarePaginator;

    public function getAdminReservationSummary(): array;

    public function findAdminReservation(int $id): Reservasi;
}
