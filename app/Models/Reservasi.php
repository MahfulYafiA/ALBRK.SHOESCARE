<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservasi extends Model
{
    use HasFactory;

    protected $table = 'tr_reservasi';

    protected $primaryKey = 'id_reservasi';

    protected $fillable = [
        'id_user',
        'tanggal_reservasi',
        'jumlah_sepatu',
        'metode_layanan',
        'alamat_jemput',
        'metode_pengembalian',
        'status_pengambilan',
        'status',
        'status_bayar',
        'tanggal_bayar',
        'metode_bayar',
        'total_harga',
        'wa_pengantaran',
        'alamat_pengantaran',
        'nama_pelanggan',
        'no_hp',
        'jenis_sepatu',
        'catatan',
        'alasan_pembatalan',
        'dibatalkan_oleh',
        'status_diubah_oleh',
    ];

    protected $casts = [
        'tanggal_reservasi' => 'date',
        'tanggal_bayar' => 'datetime',
        'total_harga' => 'integer',
        'jumlah_sepatu' => 'integer',
    ];

    /**
     * Get the user that owns this reservation
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function getPembayaranAttribute(): ?object
    {
        if (! $this->status_bayar && ! $this->metode_bayar && ! $this->tanggal_bayar) {
            return null;
        }

        return (object) [
            'id_reservasi' => $this->id_reservasi,
            'jumlah' => $this->total_harga,
            'metode' => $this->metode_bayar,
            'metode_bayar' => $this->metode_bayar,
            'status' => strtolower((string) $this->status_bayar) === 'lunas' ? 'lunas' : 'menunggu',
            'tanggal' => $this->tanggal_bayar,
            'tanggal_bayar' => $this->tanggal_bayar,
            'bukti_pembayaran' => null,
        ];
    }

    /**
     * Get the details for this reservation
     */
    public function detailReservasis(): HasMany
    {
        return $this->hasMany(DetailReservasi::class, 'id_reservasi', 'id_reservasi');
    }

    public function pembayaranRecords(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'id_reservasi', 'id_reservasi')->latest('id_pembayaran');
    }

    public function pembatalOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibatalkan_oleh', 'id_user');
    }

    public function statusDiubahOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'status_diubah_oleh', 'id_user');
    }

    public function getDetailAttribute()
    {
        return $this->relationLoaded('detailReservasis')
            ? $this->detailReservasis
            : $this->detailReservasis()->get();
    }

    /**
     * Check if reservation is still active
     */
    public function isActive(): bool
    {
        return ! in_array($this->status, ['dibatalkan', 'diambil']);
    }

    /**
     * Get formatted total harga
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp '.number_format($this->total_harga, 0, ',', '.');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu',
            'di_terima' => 'Diterima',
            'sedang_diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            'diambil' => 'Diambil',
            'dibatalkan' => 'Dibatalkan',
            default => $this->status,
        };
    }
}
