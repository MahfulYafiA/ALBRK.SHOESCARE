<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tr_reservasi', function (Blueprint $table) {
            // ✅ Kita hapus kolom yang sudah tidak terpakai di tabel utama
            // Karena sekarang data ini sudah pindah ke tr_detail_reservasi
            if (Schema::hasColumn('tr_reservasi', 'jumlah_sepatu')) {
                $table->dropColumn('jumlah_sepatu');
            }

            // Jika kolom id_layanan juga masih ada di tabel ini, hapus juga:
            if (Schema::hasColumn('tr_reservasi', 'id_layanan')) {
                $table->dropColumn('id_layanan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tr_reservasi', function (Blueprint $table) {
            // Untuk rollback (opsional)
            $table->integer('jumlah_sepatu')->nullable();
            $table->unsignedBigInteger('id_layanan')->nullable();
        });
    }
};
