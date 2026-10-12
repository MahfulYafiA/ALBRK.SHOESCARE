<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tr_pembayaran', function (Blueprint $table) {
            // ✅ Menghapus kolom sisa yang bikin error
            if (Schema::hasColumn('tr_pembayaran', 'status_pembayaran')) {
                $table->dropColumn('status_pembayaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tr_pembayaran', function (Blueprint $table) {
            $table->string('status_pembayaran')->nullable();
        });
    }
};
