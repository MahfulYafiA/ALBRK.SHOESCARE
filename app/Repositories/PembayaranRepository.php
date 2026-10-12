<?php

namespace App\Repositories;

use App\Models\Pembayaran;
use App\Repositories\Contracts\PembayaranRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class PembayaranRepository implements PembayaranRepositoryInterface
{
    public function getAll(): Collection
    {
        return Pembayaran::with('reservasi')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByReservasi(int $reservasiId): ?Pembayaran
    {
        return Pembayaran::where('id_reservasi', $reservasiId)->orderByDesc('id_pembayaran')->first();
    }

    public function findById(int $id): ?Pembayaran
    {
        return Pembayaran::with('reservasi')->find($id);
    }

    public function create(array $data): Pembayaran
    {
        return Pembayaran::create($data);
    }

    public function update(Pembayaran $pembayaran, array $data): Pembayaran
    {
        $pembayaran->update($data);

        return $pembayaran->fresh();
    }

    public function updateStatus(int $id, string $status): Pembayaran
    {
        $pembayaran = $this->findById($id);
        $updateData = ['status' => $status];

        if ($status === 'lunas') {
            $updateData['tanggal_bayar'] = Carbon::now();
        }

        $pembayaran->update($updateData);

        return $pembayaran->fresh();
    }

    public function findByOrderId(string $orderId): ?Pembayaran
    {
        return Pembayaran::where('midtrans_order_id', $orderId)->first();
    }
}
