<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'ms_layanan';

    protected $primaryKey = 'id_layanan';

    public $timestamps = false;

    protected $fillable = [
        'nama_layanan',
        'harga',
        'deskripsi',
        'gambar',
        'status',
    ];

    protected $casts = [
        'harga' => 'integer',
    ];

    public function getIsActiveAttribute(): bool
    {
        return ($this->attributes['status'] ?? 'Aktif') === 'Aktif';
    }

    public function setIsActiveAttribute($value): void
    {
        $this->attributes['status'] = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'Aktif' : 'Nonaktif';
    }

    /**
     * Get all detail reservasis for this layanan
     */
    public function detailReservasis(): HasMany
    {
        return $this->hasMany(DetailReservasi::class, 'id_layanan', 'id_layanan');
    }

    /**
     * Scope for active services
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Aktif');
    }

    /**
     * Format harga untuk display
     */
    public function getFormattedHargaAttribute(): string
    {
        return 'Rp '.number_format($this->harga, 0, ',', '.');
    }
}
