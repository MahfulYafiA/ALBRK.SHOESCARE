<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservasiIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() || $this->user()?->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['di_terima', 'sedang_diproses', 'selesai', 'diambil', 'dibatalkan'])],
            'id_layanan' => ['nullable', 'integer', 'exists:ms_layanan,id_layanan'],
            'metode_layanan' => ['nullable', Rule::in(['Pick-up', 'Drop-off'])],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_akhir' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ];
    }
}
