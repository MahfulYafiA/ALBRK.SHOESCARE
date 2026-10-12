<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanans = [
            [
                'nama_layanan' => 'Fast Clean',
                'deskripsi' => 'Pembersihan instan untuk bagian luar sepatu (upper & midsole). Cocok untuk perawatan harian sepatu yang tidak terlalu kotor.',
                'harga' => 20000,
            ],
            [
                'nama_layanan' => 'Deep Clean',
                'deskripsi' => 'Pembersihan menyeluruh meliputi luar, dalam (insole), dan tali sepatu. Menghilangkan noda membandel dan bau tidak sedap.',
                'harga' => 25000,
            ],
            [
                'nama_layanan' => 'Unyellowing',
                'deskripsi' => 'Treatment khusus untuk mengembalikan warna sol sepatu karet yang menguning (oksidasi) menjadi kembali putih bersih seperti baru.',
                'harga' => 30000,
            ],
        ];

        foreach ($layanans as $layanan) {
            Layanan::create($layanan);
        }
    }
}
