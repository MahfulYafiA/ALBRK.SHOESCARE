<?php

namespace App\DTOs;

use App\Models\Layanan;

class LayananDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $namaLayanan,
        public readonly int $harga,
        public readonly ?string $deskripsi = null,
        public readonly ?string $gambar = null,
        public readonly bool $isActive = true,
    ) {}

    public static function fromModel(Layanan $layanan): self
    {
        return new self(
            id: $layanan->id_layanan,
            namaLayanan: $layanan->nama_layanan,
            harga: $layanan->harga,
            deskripsi: $layanan->deskripsi,
            gambar: $layanan->gambar,
            isActive: $layanan->is_active ?? true,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nama_layanan' => $this->namaLayanan,
            'harga' => $this->harga,
            'deskripsi' => $this->deskripsi,
            'gambar' => $this->gambar,
            'is_active' => $this->isActive,
        ];
    }

    public function getFormattedHarga(): string
    {
        return 'Rp '.number_format($this->harga, 0, ',', '.');
    }
}
