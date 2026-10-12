<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LaporanIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() || $this->user()?->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'periode' => ['nullable', Rule::in(['hari_ini', 'minggu_ini', 'bulan_ini', 'tahun_ini', 'custom'])],
            'tanggal_mulai' => ['nullable', 'required_if:periode,custom', 'date'],
            'tanggal_akhir' => ['nullable', 'required_if:periode,custom', 'date', 'after_or_equal:tanggal_mulai'],
            'q' => ['nullable', 'string', 'max:100'],
        ];
    }
}
