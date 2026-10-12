<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ms_layanan', function (Blueprint $table) {
            // Menambahkan kolom gambar, boleh kosong, diletakkan setelah deskripsi
            $table->string('gambar')->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('ms_layanan', function (Blueprint $table) {
            $table->dropColumn('gambar');
        });
    }
};
