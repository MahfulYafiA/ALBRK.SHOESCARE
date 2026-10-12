<?php

namespace App\Http\Requests\Reservasi;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_layanans' => 'required|array|min:1',
            'id_layanans.*' => 'required|integer|distinct|exists:ms_layanan,id_layanan',
            'jumlah_layanan' => 'required|array',
            'jumlah_layanan.*' => 'required|integer|min:1|max:10',
            'tanggal_reservasi' => 'required|date|after_or_equal:today',
            'metode_layanan' => 'required|in:Drop-off,Pick-up',
            'alamat_jemput' => 'nullable|string|max:255|required_if:metode_layanan,Pick-up',
            'metode_pengembalian' => 'required|in:diambil,diantar',
            'wa_pengantaran' => 'nullable|string|max:15|required_if:metode_pengembalian,diantar',
            'alamat_pengantaran' => 'nullable|string|max:255|required_if:metode_pengembalian,diantar',
            'catatan' => 'nullable|string|max:500',
            'metode_pembayaran' => 'required|in:cash,Payment Gateway',
        ];
    }

    public function messages(): array
    {
        return [
            'id_layanans.required' => 'Pilih minimal satu layanan.',
            'id_layanans.min' => 'Pilih minimal satu layanan.',
            'id_layanans.*.exists' => 'Salah satu layanan tidak ditemukan.',
            'jumlah_layanan.required' => 'Jumlah sepatu untuk setiap layanan wajib diisi.',
            'jumlah_layanan.*.required' => 'Jumlah sepatu minimal 1 pasang untuk setiap layanan yang dipilih.',
            'jumlah_layanan.*.integer' => 'Jumlah sepatu harus berupa angka bulat.',
            'jumlah_layanan.*.min' => 'Jumlah sepatu minimal 1 pasang.',
            'jumlah_layanan.*.max' => 'Jumlah sepatu maksimal 10 pasang per layanan.',
            'tanggal_reservasi.required' => 'Tanggal reservasi wajib dipilih.',
            'tanggal_reservasi.date' => 'Tanggal reservasi tidak valid.',
            'tanggal_reservasi.after_or_equal' => 'Tanggal reservasi tidak boleh sebelum hari ini.',
            'metode_layanan.required' => 'Metode layanan wajib dipilih.',
            'metode_layanan.in' => 'Metode layanan tidak valid.',
            'alamat_jemput.required_if' => 'Alamat penjemputan wajib diisi untuk layanan Pick-up.',
            'metode_pengembalian.required' => 'Metode pengembalian wajib dipilih.',
            'metode_pengembalian.in' => 'Metode pengembalian tidak valid.',
            'wa_pengantaran.required_if' => 'Nomor WhatsApp wajib diisi untuk pengantaran.',
            'alamat_pengantaran.required_if' => 'Alamat pengantaran wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $quantities = (array) $this->input('jumlah_layanan', []);

            foreach ((array) $this->input('id_layanans', []) as $serviceId) {
                if (! array_key_exists((string) $serviceId, $quantities) && ! array_key_exists((int) $serviceId, $quantities)) {
                    $validator->errors()->add('jumlah_layanan', 'Jumlah sepatu wajib diisi untuk setiap layanan yang dipilih.');

                    return;
                }
            }
        });
    }
}
