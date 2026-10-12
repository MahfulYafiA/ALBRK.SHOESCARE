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
        Schema::create('tr_detail_reservasi', function (Blueprint $table) {
            // PK sesuai ERD
            $table->increments('id_detail');

            // Foreign Key (Kabel) ke Tabel Reservasi & Layanan
            $table->unsignedInteger('id_reservasi');
            $table->unsignedInteger('id_layanan');

            // Harga saat transaksi (untuk mengunci history harga)
            $table->integer('harga');

            // Definisi Relasi (Kabel)
            $table->foreign('id_reservasi')
                ->references('id_reservasi')
                ->on('tr_reservasi')
                ->onDelete('cascade');

            $table->foreign('id_layanan')
                ->references('id_layanan')
                ->on('ms_layanan')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_detail_reservasi');
    }
};
