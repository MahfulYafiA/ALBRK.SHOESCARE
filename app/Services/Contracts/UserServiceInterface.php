<?php

namespace App\Services\Contracts;

interface UserServiceInterface
{
    /**
     * Get all users
     */
    public function getAllUsers(): array;

    /**
     * Get users by role
     */
    public function getUsersByRole(int $role): array;

    /**
     * Find user by ID
     */
    public function findUser(int $id): ?array;

    /**
     * Create new user
     */
    public function createUser(array $data): array;

    /**
     * Update user
     */
    public function updateUser(int $id, array $data): array;

    /**
     * Delete user
     */
    public function toggleUserStatus(int $id): array;

    public function deleteUser(int $id, int $actorId): array;

    /**
     * Get total pelanggan
     */
    public function getTotalPelanggan(): int;
}
