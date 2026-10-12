<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pelanggan\PembayaranIndexRequest;
use App\ViewModels\Pelanggan\PembayaranIndexViewModel;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    public function __construct(private PembayaranIndexViewModel $pembayaranViewModel) {}

    public function index(PembayaranIndexRequest $request): View
    {
        return view('pelanggan.pembayaran.index', $this->pembayaranViewModel->getIndexData(
            (int) $request->user()->getKey(),
            $request->validated(),
        ));
    }

    public function show(int $id, PembayaranIndexRequest $request): View
    {
        return view('pelanggan.pembayaran.show', $this->pembayaranViewModel->getDetailData(
            $id,
            (int) $request->user()->getKey(),
        ));
    }
}
