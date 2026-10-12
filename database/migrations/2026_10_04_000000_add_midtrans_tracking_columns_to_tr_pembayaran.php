<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tr_pembayaran')) {
            Schema::create('tr_pembayaran', function (Blueprint $table): void {
                $table->increments('id_pembayaran');
                $table->unsignedInteger('id_reservasi');
                $table->integer('jumlah')->nullable();
                $table->string('metode_bayar', 50)->nullable();
                $table->string('metode', 50)->nullable();
                $table->string('status', 30)->default('menunggu');
                $table->dateTime('tanggal')->nullable();
                $table->dateTime('tanggal_bayar')->nullable();
                $table->string('bukti_pembayaran', 150)->nullable();
                $table->string('snap_token')->nullable();
                $table->string('midtrans_order_id', 100)->nullable()->index();
                $table->string('midtrans_transaction_id', 100)->nullable();
                $table->timestamps();
                $table->foreign('id_reservasi')->references('id_reservasi')->on('tr_reservasi')->cascadeOnDelete();
            });

            return;
        }

        Schema::table('tr_pembayaran', function (Blueprint $table): void {
            if (! Schema::hasColumn('tr_pembayaran', 'status')) {
                $table->string('status', 30)->default('menunggu');
            }
            if (! Schema::hasColumn('tr_pembayaran', 'metode')) {
                $table->string('metode', 50)->nullable();
            }
            if (! Schema::hasColumn('tr_pembayaran', 'midtrans_order_id')) {
                $table->string('midtrans_order_id', 100)->nullable()->index();
            }
            if (! Schema::hasColumn('tr_pembayaran', 'midtrans_transaction_id')) {
                $table->string('midtrans_transaction_id', 100)->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('tr_pembayaran')) {
            return;
        }

        Schema::table('tr_pembayaran', function (Blueprint $table): void {
            foreach (['midtrans_transaction_id', 'midtrans_order_id', 'metode', 'status'] as $column) {
                if (Schema::hasColumn('tr_pembayaran', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
