<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tr_pembayaran', function (Blueprint $table) {
            // ✅ Menambahkan kolom yang diminta oleh SQL Error tadi
            if (! Schema::hasColumn('tr_pembayaran', 'tanggal')) {
                $table->dateTime('tanggal')->after('id_reservasi')->nullable();
            }
            if (! Schema::hasColumn('tr_pembayaran', 'jumlah')) {
                $table->integer('jumlah')->after('tanggal')->nullable();
            }

            // Sekalian pastikan kolom metode_bayar ada
            if (! Schema::hasColumn('tr_pembayaran', 'metode_bayar')) {
                $table->string('metode_bayar')->after('jumlah')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tr_pembayaran', function (Blueprint $table) {
            $table->dropColumn(['tanggal', 'jumlah', 'metode_bayar']);
        });
    }
};
