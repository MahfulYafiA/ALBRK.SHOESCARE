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
        Schema::create('tr_reservasi', function (Blueprint $table) {
            // Primary Key sesuai ERD
            $table->increments('id_reservasi');

            // Kolom Foreign Key (harus unsignedInteger agar cocok dengan increments)
            $table->unsignedInteger('id_user');

            $table->date('tanggal_reservasi');
            $table->tinyInteger('jumlah_sepatu'); // Sesuai TINYINT(3) di Workbench
            $table->string('metode_layanan', 20); // Strict: 20 karakter
            $table->text('alamat_jemput')->nullable();
            $table->string('metode_pengembalian', 20);
            $table->string('status_pengambilan', 20);
            $table->string('status', 20);
            $table->integer('total_harga');

            // Timestamps untuk created_at & updated_at
            $table->timestamps();

            $table->string('wa_pengantaran', 15)->nullable();
            $table->text('alamat_pengantaran')->nullable();

            // Pemasangan "Kabel" Relasi (Foreign Key)
            // Menghubungkan id_user di sini ke id_user di tabel ms_user
            $table->foreign('id_user')
                ->references('id_user')
                ->on('ms_user')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_reservasi');
    }
};
