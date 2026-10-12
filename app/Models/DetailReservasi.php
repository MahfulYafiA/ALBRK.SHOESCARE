<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailReservasi extends Model
{
    use HasFactory;

    protected $table = 'tr_detail_reservasi';

    protected $primaryKey = 'id_detail';

    public $timestamps = false;

    protected $fillable = [
        'id_reservasi',
        'id_layanan',
        'harga',
        'jumlah',
        'sub_total',
    ];

    protected $casts = [
        'harga' => 'integer',
        'jumlah' => 'integer',
        'sub_total' => 'integer',
    ];

    /**
     * Get the reservation for this detail
     */
    public function reservasi(): BelongsTo
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi');
    }

    /**
     * Get the layanan for this detail
     */
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    /**
     * Get formatted harga
     */
    public function getFormattedHargaAttribute(): string
    {
        return 'Rp '.number_format($this->harga, 0, ',', '.');
    }
}
