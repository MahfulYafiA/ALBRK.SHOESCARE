<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tr_pembayaran', function (Blueprint $table) {
            // ✅ 1. Hapus kolom 'metode_pembayaran' (yang bikin error tadi)
            if (Schema::hasColumn('tr_pembayaran', 'metode_pembayaran')) {
                $table->dropColumn('metode_pembayaran');
            }

            // ✅ 2. Pastikan kolom yang benar ('metode_bayar') bersifat nullable
            // agar tidak error saat proses Midtrans dimulai
            if (Schema::hasColumn('tr_pembayaran', 'metode_bayar')) {
                $table->string('metode_bayar')->nullable()->change();
            } else {
                $table->string('metode_bayar')->nullable()->after('id_reservasi');
            }

            // ✅ 3. Pastikan kolom pendukung lainnya juga bisa kosong dulu (nullable)
            $table->dateTime('tanggal')->nullable()->change();
            $table->integer('jumlah')->nullable()->change();
            $table->string('snap_token')->nullable()->after('metode_bayar');
        });
    }

    public function down(): void
    {
        Schema::table('tr_pembayaran', function (Blueprint $table) {
            $table->string('metode_pembayaran')->nullable();
        });
    }
};
