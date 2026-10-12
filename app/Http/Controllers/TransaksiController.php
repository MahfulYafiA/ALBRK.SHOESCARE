<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreOfflineTransactionRequest;
use App\Services\Contracts\OfflineTransactionServiceInterface;
use App\ViewModels\Admin\TransaksiViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    public function __construct(
        private TransaksiViewModel $transaksiViewModel,
        private OfflineTransactionServiceInterface $offlineTransactionService,
    ) {}

    /**
     * Show offline transaction form
     */
    public function createOffline(): View
    {
        return view('admin.transaksi.transaksi-luring', $this->transaksiViewModel->getCreateData());
    }

    /**
     * Store offline transaction
     */
    public function storeOffline(StoreOfflineTransactionRequest $request): RedirectResponse
    {
        try {
            $result = $this->offlineTransactionService->create(auth()->id(), $request->validated());

            if (! $result['success']) {
                return back()->with('error', $result['message'])->withInput();
            }

            return redirect()->route('admin.antrean')->with('success', $result['message']);
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Gagal menyimpan transaksi.')->withInput();
        }
    }
}
