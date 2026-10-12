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
        Schema::table('tr_reservasi', function (Blueprint $table) {
            // ✅ Menambahkan kolom status_bayar setelah kolom 'status'
            // Kita gunakan ENUM agar pilihannya terkunci (Lunas atau Belum Lunas)
            $table->enum('status_bayar', ['Belum Lunas', 'Lunas'])
                ->default('Belum Lunas')
                ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tr_reservasi', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn('status_bayar');
        });
    }
};
