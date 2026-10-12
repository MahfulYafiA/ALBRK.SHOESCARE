<?php

namespace App\Services\Transaction;

use App\Repositories\Contracts\DetailReservasiRepositoryInterface;
use App\Repositories\Contracts\LayananRepositoryInterface;
use App\Repositories\Contracts\PembayaranRepositoryInterface;
use App\Repositories\Contracts\ReservasiRepositoryInterface;
use App\Services\Contracts\OfflineTransactionServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OfflineTransactionService implements OfflineTransactionServiceInterface
{
    public function __construct(
        private LayananRepositoryInterface $layananRepository,
        private PembayaranRepositoryInterface $pembayaranRepository,
        private ReservasiRepositoryInterface $reservasiRepository,
        private DetailReservasiRepositoryInterface $detailReservasiRepository,
    ) {}

    public function create(int $userId, array $data): array
    {
        return DB::transaction(function () use ($userId, $data): array {
            $layanan = $this->layananRepository->findById((int) $data['id_layanan']);

            if (! $layanan || ! $layanan->is_active) {
                return ['success' => false, 'message' => 'Layanan tidak tersedia.'];
            }

            $jumlah = (int) $data['jumlah_sepatu'];
            $totalHarga = $layanan->harga * $jumlah;

            $reservasi = $this->reservasiRepository->create([
                'id_user' => $userId,
                'nama_pelanggan' => $data['nama'],
                'no_hp' => $data['no_telp'] ?? null,
                'jenis_sepatu' => '-',
                'tanggal_reservasi' => Carbon::today()->toDateString(),
                'jumlah_sepatu' => $jumlah,
                'metode_layanan' => $data['metode_layanan'],
                'alamat_jemput' => $data['alamat'] ?? null,
                'status' => 'di_terima',
                'status_bayar' => 'Lunas',
                'tanggal_bayar' => Carbon::now(),
                'metode_bayar' => $data['metode_bayar'],
                'total_harga' => $totalHarga,
                'catatan' => $data['catatan'] ?? null,
            ]);

            $this->pembayaranRepository->create([
                'id_reservasi' => $reservasi->id_reservasi,
                'jumlah' => $totalHarga,
                'metode' => strtolower((string) $reservasi->metode_bayar),
                'metode_bayar' => $reservasi->metode_bayar,
                'status' => 'lunas',
                'tanggal_bayar' => Carbon::now(),
            ]);

            $this->detailReservasiRepository->create([
                'id_reservasi' => $reservasi->id_reservasi,
                'id_layanan' => $layanan->id_layanan,
                'harga' => $layanan->harga,
                'jumlah' => $jumlah,
                'sub_total' => $totalHarga,
            ]);

            return ['success' => true, 'message' => 'Transaksi offline berhasil disimpan.'];
        });
    }
}
