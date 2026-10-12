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
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            // Email sebagai Primary Key sesuai gambar ERD (Kunci Kuning)
            // Menggunakan 191 agar aman untuk indexing database
            $table->string('email', 191)->primary();

            $table->string('token', 191);

            // created_at untuk mencatat kapan token dibuat (biasanya hangus dalam 60 menit)
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
