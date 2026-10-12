<?php

namespace App\Repositories;

use App\Models\Layanan;
use App\Repositories\Contracts\LayananRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LayananRepository implements LayananRepositoryInterface
{
    public function getAll(): Collection
    {
        return Layanan::orderBy('nama_layanan')->get();
    }

    public function getActive(): Collection
    {
        return Layanan::where('status', 'Aktif')
            ->orderBy('nama_layanan')
            ->get();
    }

    public function findById(int $id): ?Layanan
    {
        return Layanan::find($id);
    }

    public function create(array $data): Layanan
    {
        return Layanan::create($data);
    }

    public function update(Layanan $layanan, array $data): Layanan
    {
        $layanan->update($data);

        return $layanan->fresh();
    }

    public function delete(int $id): bool
    {
        $layanan = $this->findById($id);

        return $layanan ? $layanan->delete() : false;
    }
}
