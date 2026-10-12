<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AntreanIndexRequest;
use App\Http\Requests\Admin\CancelAntreanRequest;
use App\Http\Requests\Admin\UpdateAntreanStatusRequest;
use App\Services\Contracts\AntreanServiceInterface;
use App\ViewModels\Admin\AntreanViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AntreanController extends Controller
{
    public function __construct(
        private AntreanViewModel $antreanViewModel,
        private AntreanServiceInterface $antreanService,
    ) {}

    public function index(AntreanIndexRequest $request): View
    {
        return view('admin.antrean.index', $this->antreanViewModel->getIndexData($request->validated()));
    }

    public function show(int $id): View
    {
        return view('admin.antrean.show', ['reservasi' => $this->antreanViewModel->find($id)]);
    }

    public function updateStatus(UpdateAntreanStatusRequest $request, int $id): RedirectResponse
    {
        $this->antreanService->updateStatus($id, $request->validated('status'), (int) $request->user()->getKey());

        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }

    public function cancel(CancelAntreanRequest $request, int $id): RedirectResponse
    {
        $this->antreanService->cancel($id, $request->validated('alasan_pembatalan'), (int) $request->user()->getKey());

        return redirect()->route('admin.antrean.show', $id)->with('success', 'Reservasi dibatalkan dan alasannya disimpan.');
    }
}
