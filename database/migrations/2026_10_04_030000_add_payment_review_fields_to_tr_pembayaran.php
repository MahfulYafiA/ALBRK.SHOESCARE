<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tr_pembayaran')) {
            return;
        }

        Schema::table('tr_pembayaran', function (Blueprint $table): void {
            if (! Schema::hasColumn('tr_pembayaran', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable();
            }
            if (! Schema::hasColumn('tr_pembayaran', 'diproses_oleh')) {
                $table->unsignedInteger('diproses_oleh')->nullable();
                $table->foreign('diproses_oleh')->references('id_user')->on('ms_user')->nullOnDelete();
            }
            if (! Schema::hasColumn('tr_pembayaran', 'diproses_pada')) {
                $table->dateTime('diproses_pada')->nullable();
            }
        });

        // Older cash reservations were marked paid but had no corresponding payment row.
        if (Schema::hasTable('tr_reservasi')) {
            DB::table('tr_reservasi as r')
                ->leftJoin('tr_pembayaran as p', 'p.id_reservasi', '=', 'r.id_reservasi')
                ->whereNull('p.id_pembayaran')
                ->whereRaw('LOWER(COALESCE(r.status_bayar, ?)) = ?', ['', 'lunas'])
                ->whereNotNull('r.metode_bayar')
                ->select(['r.id_reservasi', 'r.total_harga', 'r.metode_bayar', 'r.tanggal_bayar', 'r.created_at', 'r.updated_at'])
                ->orderBy('r.id_reservasi')
                ->chunkById(100, function ($reservations): void {
                    $now = now();
                    $rows = $reservations->map(fn ($reservation): array => [
                        'id_reservasi' => $reservation->id_reservasi,
                        'jumlah' => $reservation->total_harga,
                        'metode' => strtolower((string) $reservation->metode_bayar),
                        'metode_bayar' => $reservation->metode_bayar,
                        'status' => 'lunas',
                        'tanggal_bayar' => $reservation->tanggal_bayar ?? $reservation->created_at ?? $now,
                        'created_at' => $reservation->created_at ?? $now,
                        'updated_at' => $reservation->updated_at ?? $now,
                    ])->all();

                    if ($rows) {
                        DB::table('tr_pembayaran')->insert($rows);
                    }
                }, 'r.id_reservasi', 'id_reservasi');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('tr_pembayaran')) {
            return;
        }

        Schema::table('tr_pembayaran', function (Blueprint $table): void {
            if (Schema::hasColumn('tr_pembayaran', 'diproses_oleh')) {
                $table->dropForeign(['diproses_oleh']);
                $table->dropColumn('diproses_oleh');
            }
            foreach (['alasan_penolakan', 'diproses_pada'] as $column) {
                if (Schema::hasColumn('tr_pembayaran', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
