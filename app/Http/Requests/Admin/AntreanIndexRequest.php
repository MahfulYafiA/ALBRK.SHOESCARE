<?php

namespace App\Http\Requests\Admin;

use App\Enums\StatusReservasi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AntreanIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() || $this->user()?->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(array_column(StatusReservasi::cases(), 'value'))],
            'id_layanan' => ['nullable', 'integer', 'exists:ms_layanan,id_layanan'],
            'metode_layanan' => ['nullable', Rule::in(['Pick-up', 'Drop-off'])],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_akhir' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'sort' => ['nullable', Rule::in(['terbaru', 'terlama', 'total_terbesar', 'jadwal_terdekat'])],
        ];
    }
}
