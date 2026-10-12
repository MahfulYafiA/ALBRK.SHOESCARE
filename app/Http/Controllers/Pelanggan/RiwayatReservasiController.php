<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pelanggan\RiwayatReservasiRequest;
use App\ViewModels\Pelanggan\RiwayatReservasiViewModel;
use Illuminate\View\View;

class RiwayatReservasiController extends Controller
{
    public function __construct(private RiwayatReservasiViewModel $riwayatViewModel) {}

    public function index(RiwayatReservasiRequest $request): View
    {
        return view('pelanggan.reservasi.riwayat-reservasi.index', $this->riwayatViewModel->getIndexData(
            (int) $request->user()->getKey(),
            $request->validated(),
        ));
    }

    public function show(int $id, RiwayatReservasiRequest $request): View
    {
        return view('pelanggan.reservasi.riwayat-reservasi.show', $this->riwayatViewModel->getDetailData(
            $id,
            (int) $request->user()->getKey(),
        ));
    }
}
