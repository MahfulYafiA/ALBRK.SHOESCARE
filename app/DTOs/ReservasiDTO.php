<?php

namespace App\DTOs;

use App\Models\Reservasi;

class ReservasiDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $idUser,
        public readonly string $tanggalReservasi,
        public readonly int $jumlahSepatu,
        public readonly string $metodeLayanan,
        public readonly string $metodePengembalian,
        public readonly string $status,
        public readonly int $totalHarga,
        public readonly ?string $alamatJemput = null,
        public readonly ?string $namaPelanggan = null,
        public readonly ?string $noHp = null,
        public readonly ?string $jenisSepatu = null,
        public readonly ?string $catatan = null,
        public readonly array $detailLayanan = [],
    ) {}

    public static function fromModel(Reservasi $reservasi): self
    {
        return new self(
            id: $reservasi->id_reservasi,
            idUser: $reservasi->id_user,
            tanggalReservasi: $reservasi->tanggal_reservasi?->format('Y-m-d') ?? (string) $reservasi->created_at?->format('Y-m-d'),
            jumlahSepatu: (int) ($reservasi->jumlah_sepatu ?? 1),
            metodeLayanan: (string) ($reservasi->metode_layanan ?? '-'),
            metodePengembalian: (string) ($reservasi->metode_pengembalian ?? $reservasi->metode_keluar ?? '-'),
            status: (string) ($reservasi->status ?? '-'),
            totalHarga: (int) ($reservasi->total_harga ?? 0),
            alamatJemput: $reservasi->alamat_jemput,
            namaPelanggan: $reservasi->nama_pelanggan,
            noHp: $reservasi->no_hp,
            jenisSepatu: $reservasi->jenis_sepatu,
            catatan: $reservasi->catatan,
            detailLayanan: $reservasi->detailReservasis->map(fn ($detail): array => [
                'nama_layanan' => $detail->layanan?->nama_layanan ?? 'Layanan',
                'jumlah' => (int) ($detail->jumlah ?? 1),
                'harga' => (int) ($detail->harga ?? 0),
                'sub_total' => (int) ($detail->sub_total ?? (($detail->harga ?? 0) * ($detail->jumlah ?? 1))),
            ])->all(),
        );
    }

    public function toArray(): array
    {
        return [
            'id_reservasi' => $this->id,
            'id_user' => $this->idUser,
            'tanggal_reservasi' => $this->tanggalReservasi,
            'jumlah_sepatu' => $this->jumlahSepatu,
            'metode_layanan' => $this->metodeLayanan,
            'metode_pengembalian' => $this->metodePengembalian,
            'status' => $this->status,
            'total_harga' => $this->totalHarga,
            'alamat_jemput' => $this->alamatJemput,
            'nama_pelanggan' => $this->namaPelanggan,
            'no_hp' => $this->noHp,
            'jenis_sepatu' => $this->jenisSepatu,
            'catatan' => $this->catatan,
            'detail_layanan' => $this->detailLayanan,
        ];
    }

    public function getFormattedTotal(): string
    {
        return 'Rp '.number_format($this->totalHarga, 0, ',', '.');
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu',
            'di_terima' => 'Diterima',
            'sedang_diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            'diambil' => 'Diambil',
            'dibatalkan' => 'Dibatalkan',
            default => $this->status,
        };
    }
}
