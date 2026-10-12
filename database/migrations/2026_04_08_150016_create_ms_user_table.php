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
        Schema::create('ms_user', function (Blueprint $table) {
            $table->increments('id_user'); // PK INT di Workbench
            $table->string('nama', 40);
            $table->string('email', 50)->unique();
            $table->string('password', 60);

            $table->string('google_id', 30)->nullable();
            $table->string('no_telp', 15)->nullable();
            $table->string('alamat', 200)->nullable(); // Sesuaikan dengan VARCHAR(200) tadi
            $table->string('foto_profil', 150)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->enum('status', ['Aktif', 'Nonaktif'])->nullable();
            $table->enum('role', ['superadmin', 'admin', 'pelanggan'])->default('pelanggan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_user');
    }
};
