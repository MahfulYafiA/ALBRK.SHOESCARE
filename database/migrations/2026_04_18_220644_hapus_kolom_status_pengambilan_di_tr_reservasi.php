<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tr_reservasi', function (Blueprint $table) {
            // ✅ Menghapus kolom status_pengambilan yang sudah tidak dipakai
            if (Schema::hasColumn('tr_reservasi', 'status_pengambilan')) {
                $table->dropColumn('status_pengambilan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tr_reservasi', function (Blueprint $table) {
            // Untuk rollback jika diperlukan
            $table->string('status_pengambilan')->default('Belum Diambil');
        });
    }
};
