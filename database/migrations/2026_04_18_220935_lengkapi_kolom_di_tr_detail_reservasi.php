<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tr_detail_reservasi', function (Blueprint $table) {
            // ✅ Menambahkan kolom yang dibutuhkan untuk menyimpan detail pesanan
            // Kita cek dulu supaya tidak error kalau kolomnya ternyata sudah ada
            if (! Schema::hasColumn('tr_detail_reservasi', 'harga')) {
                $table->integer('harga')->after('id_layanan');
            }
            if (! Schema::hasColumn('tr_detail_reservasi', 'jumlah')) {
                $table->integer('jumlah')->after('harga');
            }
            if (! Schema::hasColumn('tr_detail_reservasi', 'sub_total')) {
                $table->integer('sub_total')->after('jumlah');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tr_detail_reservasi', function (Blueprint $table) {
            $table->dropColumn(['harga', 'jumlah', 'sub_total']);
        });
    }
};
