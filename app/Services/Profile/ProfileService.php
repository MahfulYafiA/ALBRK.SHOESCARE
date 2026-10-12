<?php

namespace App\Services\Profile;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\ProfileServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService implements ProfileServiceInterface
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function updateProfile(User $user, array $data): User
    {
        return $this->userRepository->update($user, [
            'nama' => $data['nama'],
            'email' => $data['email'],
            'no_telp' => $data['no_hp'] ?? null,
            'alamat' => $data['alamat'] ?? null,
        ]);
    }

    public function updatePhoto(User $user, UploadedFile $photo): User
    {
        $oldPhoto = $user->foto_profil;
        $newPhoto = $photo->store('profil', 'public');
        if (! $newPhoto) {
            throw new \RuntimeException('Foto profil gagal disimpan. Silakan coba lagi.');
        }

        try {
            $updatedUser = $this->userRepository->update($user, ['foto_profil' => $newPhoto]);
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($newPhoto);
            throw $exception;
        }

        if ($oldPhoto && $oldPhoto !== $newPhoto && Storage::disk('public')->exists($oldPhoto)) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return $updatedUser;
    }

    public function updatePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (! Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $this->userRepository->update($user, ['password' => $newPassword]);

        return true;
    }

    public function deletePhoto(User $user): User
    {
        $this->deleteStoredPhoto($user);

        return $this->userRepository->update($user, ['foto_profil' => null]);
    }

    private function deleteStoredPhoto(User $user): void
    {
        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }
    }
}
