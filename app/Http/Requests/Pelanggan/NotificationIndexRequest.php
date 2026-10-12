<?php

namespace App\Http\Requests\Pelanggan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotificationIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'filter' => ['nullable', Rule::in(['semua', 'belum_dibaca', 'pembayaran', 'reservasi', 'informasi'])],
            'q' => ['nullable', 'string', 'max:100'],
        ];
    }
}
