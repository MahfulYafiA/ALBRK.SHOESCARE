<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'nama' => 'required|string|max:40',
            'email' => 'required|email|max:50|unique:ms_user,email,'.$userId.',id_user',
            'no_hp' => ['nullable', 'string', 'max:15', 'regex:/^[0-9+() .-]{7,15}$/'],
            'alamat' => 'nullable|string|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'nama.max' => 'Nama maksimal 40 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan akun lain.',
            'no_hp.max' => 'Nomor WhatsApp maksimal 15 angka.',
            'no_hp.regex' => 'Format nomor telepon tidak valid.',
            'alamat.max' => 'Alamat maksimal 200 karakter.',
        ];
    }
}
