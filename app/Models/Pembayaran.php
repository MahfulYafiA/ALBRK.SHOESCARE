<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'tr_pembayaran';

    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_reservasi',
        'jumlah',
        'metode',
        'metode_bayar',
        'status',
        'tanggal_bayar',
        'bukti_pembayaran',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'snap_token',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'tanggal_bayar' => 'datetime',
        'diproses_pada' => 'datetime',
    ];

    /**
     * Get the reservation for this payment
     */
    public function reservasi(): BelongsTo
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh', 'id_user');
    }

    /**
     * Check if payment is successful
     */
    public function isLunas(): bool
    {
        return $this->status === 'lunas';
    }

    /**
     * Get formatted jumlah
     */
    public function getFormattedJumlahAttribute(): string
    {
        return 'Rp '.number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu',
            'lunas' => 'Lunas',
            'gagal' => 'Gagal',
            'dikembalikan' => 'Dikembalikan',
            default => $this->status,
        };
    }
}
