<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function getAll(): Collection
    {
        return User::orderBy('created_at', 'desc')->get();
    }

    public function getPaginatedAll(int $perPage = 10): LengthAwarePaginator
    {
        return User::orderByDesc('created_at')->paginate($perPage)->withQueryString();
    }

    public function countAll(): int
    {
        return User::count();
    }

    public function getByRole(int $role): Collection
    {
        return User::where('role', $this->roleName($role))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getPaginatedByRole(int $role, int $perPage = 10): LengthAwarePaginator
    {
        return User::where('role', $this->roleName($role))
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return $user->fresh();
    }

    public function delete(int $id): bool
    {
        $user = $this->findById($id);

        return $user ? $user->delete() : false;
    }

    public function countByRole(int $role): int
    {
        return User::where('role', $this->roleName($role))->count();
    }

    public function getTotalPelanggan(): int
    {
        return $this->countByRole(3);
    }

    private function roleName(int $role): string
    {
        return match ($role) {
            1 => 'superadmin',
            2 => 'admin',
            default => 'pelanggan',
        };
    }
}
