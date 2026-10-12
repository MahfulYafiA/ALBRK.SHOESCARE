<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tr_reservasi', function (Blueprint $table): void {
            if (! Schema::hasColumn('tr_reservasi', 'alasan_pembatalan')) {
                $table->text('alasan_pembatalan')->nullable();
            }
            if (! Schema::hasColumn('tr_reservasi', 'dibatalkan_oleh')) {
                $table->unsignedInteger('dibatalkan_oleh')->nullable();
                $table->foreign('dibatalkan_oleh')->references('id_user')->on('ms_user')->nullOnDelete();
            }
            if (! Schema::hasColumn('tr_reservasi', 'status_diubah_oleh')) {
                $table->unsignedInteger('status_diubah_oleh')->nullable();
                $table->foreign('status_diubah_oleh')->references('id_user')->on('ms_user')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tr_reservasi', function (Blueprint $table): void {
            if (Schema::hasColumn('tr_reservasi', 'dibatalkan_oleh')) {
                $table->dropForeign(['dibatalkan_oleh']);
                $table->dropColumn('dibatalkan_oleh');
            }
            if (Schema::hasColumn('tr_reservasi', 'status_diubah_oleh')) {
                $table->dropForeign(['status_diubah_oleh']);
                $table->dropColumn('status_diubah_oleh');
            }
            if (Schema::hasColumn('tr_reservasi', 'alasan_pembatalan')) {
                $table->dropColumn('alasan_pembatalan');
            }
        });
    }
};
