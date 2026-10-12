<?php

namespace App\Services\User;

use App\DTOs\UserDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function getAllUsers(): array
    {
        $users = $this->userRepository->getAll();

        return $users->map(fn ($user) => UserDTO::fromModel($user)->toArray())->toArray();
    }

    public function getUsersByRole(int $role): array
    {
        $users = $this->userRepository->getByRole($role);

        return $users->map(fn ($user) => UserDTO::fromModel($user)->toArray())->toArray();
    }

    public function findUser(int $id): ?array
    {
        $user = $this->userRepository->findById($id);

        return $user ? UserDTO::fromModel($user)->toArray() : null;
    }

    public function createUser(array $data): array
    {
        $user = $this->userRepository->create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => $data['password'],
            'id_role' => $data['id_role'] ?? 2,
            'no_telp' => $data['no_telp'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'status' => $data['status'] ?? 'Aktif',
        ]);

        return [
            'success' => true,
            'user' => UserDTO::fromModel($user)->toArray(),
            'message' => 'User berhasil dibuat',
        ];
    }

    public function updateUser(int $id, array $data): array
    {
        $user = $this->userRepository->findById($id);

        if (! $user) {
            return [
                'success' => false,
                'message' => 'User tidak ditemukan',
            ];
        }

        $updateData = [
            'nama' => $data['nama'] ?? $user->nama,
            'email' => $data['email'] ?? $user->email,
            'id_role' => $data['id_role'] ?? $user->id_role,
            'no_telp' => $data['no_telp'] ?? $user->no_telp,
            'alamat' => $data['alamat'] ?? $user->alamat,
        ];

        if (isset($data['password'])) {
            $updateData['password'] = $data['password'];
        }

        $user = $this->userRepository->update($user, $updateData);

        return [
            'success' => true,
            'user' => UserDTO::fromModel($user)->toArray(),
            'message' => 'User berhasil diperbarui',
        ];
    }

    public function toggleUserStatus(int $id): array
    {
        $user = $this->userRepository->findById($id);
        if (! $user) {
            return ['success' => false, 'message' => 'User tidak ditemukan.'];
        }
        if ($user->isSuperAdmin()) {
            return ['success' => false, 'message' => 'Superadmin tidak dapat dinonaktifkan.'];
        }

        $newStatus = $user->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $this->userRepository->update($user, ['status' => $newStatus]);

        return ['success' => true, 'message' => "User berhasil di{$newStatus}kan."];
    }

    public function deleteUser(int $id, int $actorId): array
    {
        if ($id === $actorId) {
            return ['success' => false, 'message' => 'Anda tidak dapat menghapus akun sendiri.'];
        }

        $user = $this->userRepository->findById($id);
        if (! $user) {
            return ['success' => false, 'message' => 'User tidak ditemukan.'];
        }
        if ($user->isSuperAdmin()) {
            return ['success' => false, 'message' => 'Superadmin tidak dapat dihapus.'];
        }

        return $this->userRepository->delete($id)
            ? ['success' => true, 'message' => 'User berhasil dihapus.']
            : ['success' => false, 'message' => 'Gagal menghapus user.'];
    }

    public function getTotalPelanggan(): int
    {
        return $this->userRepository->getTotalPelanggan();
    }
}
