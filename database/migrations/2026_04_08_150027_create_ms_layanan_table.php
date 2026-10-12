<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ms_layanan', function (Blueprint $table) {
            // PK sesuai ERD (INT 11 Auto Increment)
            $table->increments('id_layanan');

            // Nama Layanan (Deep Clean, Unyellowing, dll)
            $table->string('nama_layanan', 50);

            // Harga (INT 11)
            $table->integer('harga');

            // Deskripsi (TEXT)
            $table->text('deskripsi')->nullable();

            // Sesuai Workbench, kita tidak pakai timestamps di tabel master ini
            // Tapi jika Mas Rofi'i ingin ada created_at, boleh biarkan $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_layanan');
    }
};
