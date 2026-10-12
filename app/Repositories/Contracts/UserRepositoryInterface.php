<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Get all users
     */
    public function getAll(): Collection;

    public function getPaginatedAll(int $perPage = 10): LengthAwarePaginator;

    public function countAll(): int;

    /**
     * Get users by role
     */
    public function getByRole(int $role): Collection;

    /** Get a paginated user list by role. */
    public function getPaginatedByRole(int $role, int $perPage = 10): LengthAwarePaginator;

    /**
     * Find user by ID
     */
    public function findById(int $id): ?User;

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User;

    /**
     * Create new user
     */
    public function create(array $data): User;

    /**
     * Update user
     */
    public function update(User $user, array $data): User;

    /**
     * Delete user
     */
    public function delete(int $id): bool;

    /**
     * Count users by role
     */
    public function countByRole(int $role): int;

    /**
     * Get total customers (role 3)
     */
    public function getTotalPelanggan(): int;
}
