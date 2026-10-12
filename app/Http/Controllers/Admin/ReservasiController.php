<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReservasiIndexRequest;
use App\Http\Requests\Admin\UpdateReservasiStatusRequest;
use App\Services\Contracts\AntreanServiceInterface;
use App\ViewModels\Admin\ReservasiViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReservasiController extends Controller
{
    public function __construct(
        private ReservasiViewModel $reservasiViewModel,
        private AntreanServiceInterface $antreanService,
    ) {}

    public function index(ReservasiIndexRequest $request): View
    {
        return view('admin.daftar-reservasi', $this->reservasiViewModel->getIndexData($request->validated()));
    }

    public function show(int $id): View
    {
        return view('admin.detail-reservasi', $this->reservasiViewModel->getDetailData($id));
    }

    public function updateStatus(UpdateReservasiStatusRequest $request, int $id): RedirectResponse
    {
        $this->antreanService->updateReservationStatus($id, $request->validated('status'), (int) $request->user()->getKey());

        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }
}
