<?php

namespace App\Services\Layanan;

use App\DTOs\LayananDTO;
use App\Repositories\Contracts\LayananRepositoryInterface;
use App\Services\Contracts\LayananServiceInterface;
use Illuminate\Support\Facades\Storage;

class LayananService implements LayananServiceInterface
{
    public function __construct(
        private LayananRepositoryInterface $layananRepository
    ) {}

    public function getAllLayanan(): array
    {
        $layanans = $this->layananRepository->getAll();

        return $layanans->map(fn ($layanan) => LayananDTO::fromModel($layanan)->toArray())->toArray();
    }

    public function getActiveLayanan(): array
    {
        $layanans = $this->layananRepository->getActive();

        return $layanans->map(fn ($layanan) => LayananDTO::fromModel($layanan)->toArray())->toArray();
    }

    public function findLayanan(int $id): ?array
    {
        $layanan = $this->layananRepository->findById($id);

        return $layanan ? LayananDTO::fromModel($layanan)->toArray() : null;
    }

    public function createLayanan(array $data): array
    {
        $gambar = null;
        if (isset($data['gambar'])) {
            $gambar = $data['gambar']->store('layanan', 'public');
        }

        $layanan = $this->layananRepository->create([
            'nama_layanan' => $data['nama_layanan'],
            'harga' => $data['harga'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'gambar' => $gambar,
            'status' => $data['status'] ?? 'Aktif',
        ]);

        return [
            'success' => true,
            'layanan' => LayananDTO::fromModel($layanan)->toArray(),
            'message' => 'Layanan berhasil dibuat',
        ];
    }

    public function updateLayanan(int $id, array $data): array
    {
        $layanan = $this->layananRepository->findById($id);

        if (! $layanan) {
            return [
                'success' => false,
                'message' => 'Layanan tidak ditemukan',
            ];
        }

        $updateData = [
            'nama_layanan' => $data['nama_layanan'] ?? $layanan->nama_layanan,
            'harga' => $data['harga'] ?? $layanan->harga,
            'deskripsi' => $data['deskripsi'] ?? $layanan->deskripsi,
            'status' => $data['status'] ?? $layanan->status ?? 'Aktif',
        ];

        if (isset($data['gambar'])) {
            // Delete old image
            if ($layanan->gambar && Storage::disk('public')->exists($layanan->gambar)) {
                Storage::disk('public')->delete($layanan->gambar);
            }
            $updateData['gambar'] = $data['gambar']->store('layanan', 'public');
        }

        $layanan = $this->layananRepository->update($layanan, $updateData);

        return [
            'success' => true,
            'layanan' => LayananDTO::fromModel($layanan)->toArray(),
            'message' => 'Layanan berhasil diperbarui',
        ];
    }

    public function deleteLayanan(int $id): bool
    {
        $layanan = $this->layananRepository->findById($id);

        if ($layanan && $layanan->gambar) {
            if (Storage::disk('public')->exists($layanan->gambar)) {
                Storage::disk('public')->delete($layanan->gambar);
            }
        }

        return $this->layananRepository->delete($id);
    }
}
