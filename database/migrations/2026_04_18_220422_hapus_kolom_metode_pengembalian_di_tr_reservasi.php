<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tr_reservasi', function (Blueprint $table) {
            // ✅ Menghapus kolom sisa yang sudah tidak dipakai di logic baru
            if (Schema::hasColumn('tr_reservasi', 'metode_pengembalian')) {
                $table->dropColumn('metode_pengembalian');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tr_reservasi', function (Blueprint $table) {
            $table->string('metode_pengembalian')->nullable();
        });
    }
};
