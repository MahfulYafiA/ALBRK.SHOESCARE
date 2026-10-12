<?php

namespace App\DTOs;

use App\Models\User;

class UserDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $nama,
        public readonly string $email,
        public readonly int $role,
        public readonly ?string $noTelp = null,
        public readonly ?string $alamat = null,
        public readonly ?string $fotoProfil = null,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id_user,
            nama: $user->nama,
            email: $user->email,
            role: $user->id_role,
            noTelp: $user->no_telp,
            alamat: $user->alamat,
            fotoProfil: $user->foto_profil,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'email' => $this->email,
            'role' => $this->role,
            'no_telp' => $this->noTelp,
            'alamat' => $this->alamat,
            'foto_profil' => $this->fotoProfil,
        ];
    }

    public function getRoleLabel(): string
    {
        return match ($this->role) {
            1 => 'Super Admin',
            2 => 'Admin',
            3 => 'Pelanggan',
            default => 'Unknown',
        };
    }
}
