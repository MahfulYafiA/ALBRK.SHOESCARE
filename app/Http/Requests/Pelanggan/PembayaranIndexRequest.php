<?php

namespace App\Http\Requests\Pelanggan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PembayaranIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isPelanggan() === true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'metode' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', Rule::in(['menunggu', 'verifikasi', 'berhasil', 'ditolak', 'dibatalkan'])],
        ];
    }
}
