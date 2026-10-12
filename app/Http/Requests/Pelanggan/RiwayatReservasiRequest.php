<?php

namespace App\Http\Requests\Pelanggan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RiwayatReservasiRequest extends FormRequest
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
            'id_layanan' => ['nullable', 'integer', 'exists:ms_layanan,id_layanan'],
            'status' => ['nullable', Rule::in(['selesai', 'dibatalkan'])],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_selesai.after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal awal.',
            'id_layanan.exists' => 'Layanan yang dipilih tidak tersedia.',
            'status.in' => 'Status riwayat tidak valid.',
        ];
    }
}
