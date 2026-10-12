<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Memanggil Seeder Layanan terlebih dahulu
        $this->call([
            LayananSeeder::class,
        ]);

        // 2. Mengisi Data User (Superadmin)
        User::create([
            'nama' => 'ALBRK Owner',
            'email' => 'superadmin@albrk.com',
            'password' => Hash::make('admin123'),
            'id_role' => 1, // 1 untuk Superadmin
            'no_telp' => '08123456789',
        ]);

        // 3. Mengisi Data User (Admin/Kasir)
        User::create([
            'nama' => 'Staf Kasir',
            'email' => 'admin@albrk.com',
            'password' => Hash::make('admin123'),
            'id_role' => 2, // 2 untuk Admin
            'no_telp' => '08555555555',
        ]);

        // 4. Mengisi Data User (Pelanggan)
        User::create([
            'nama' => 'mahful',
            'email' => 'mahful@gmail.com',
            'password' => Hash::make('mahful123'),
            'id_role' => 3, // 3 untuk Pelanggan
            'no_telp' => '08987654321',
        ]);

        $this->command->info('Database ALBRK SHOESCLEAN berhasil diisi data master!');
    }
}
