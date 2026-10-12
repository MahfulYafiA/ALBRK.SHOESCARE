<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Wajib ada untuk insert data awal

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('ms_pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Contoh: 'hero_image'
            $table->string('value')->nullable(); // Tempat simpan path gambar
            $table->timestamps();
        });

        // Insert data awal supaya sistem tidak error saat mencari key 'hero_image'
        DB::table('ms_pengaturan')->insert([
            'key' => 'hero_image',
            'value' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('ms_pengaturan')->insert([
            'key' => 'tentang_image',
            'value' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_pengaturan');
    }
};
