<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function attemptLogin(string $email, string $password): ?array
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user || ! Hash::check($password, $user->password)) {
            return null;
        }

        if (! $this->isActive($user)) {
            return ['inactive' => true];
        }

        Auth::login($user);

        return [
            'user' => $user,
        ];
    }

    public function register(array $data): array
    {
        $user = $this->userRepository->create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => $data['password'],
            'id_role' => 3, // Default Pelanggan
            'status' => 'Aktif',
        ]);

        Auth::login($user);

        return [
            'user' => $user,
            'message' => 'Registrasi berhasil!',
        ];
    }

    public function handleGoogleCallback(array $googleUser): array
    {
        $user = $this->userRepository->findByEmail($googleUser['email']);

        if ($user && ! $this->isActive($user)) {
            return [
                'user' => $user,
                'message' => 'Akun Anda nonaktif. Hubungi administrator.',
                'inactive' => true,
            ];
        }

        if (! $user) {
            // Create new user from Google data
            $user = $this->userRepository->create([
                'nama' => $googleUser['name'],
                'email' => $googleUser['email'],
                'google_id' => $googleUser['id'],
                'id_role' => 3, // Default Pelanggan
                'status' => 'Aktif',
            ]);
        } elseif (! $user->google_id) {
            // Link Google account to existing user
            $this->userRepository->update($user, ['google_id' => $googleUser['id']]);
        }

        Auth::login($user);

        return [
            'user' => $user,
            'message' => 'Login dengan Google berhasil!',
        ];
    }

    public function logout(): void
    {
        Auth::logout();
    }

    private function isActive(User $user): bool
    {
        return strtolower(trim((string) $user->status)) === 'aktif';
    }
}
