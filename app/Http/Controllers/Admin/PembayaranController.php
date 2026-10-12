<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminPaymentIndexRequest;
use App\Http\Requests\Admin\RejectPaymentRequest;
use App\Services\Contracts\PaymentReviewServiceInterface;
use App\ViewModels\Admin\PembayaranViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    public function __construct(
        private PembayaranViewModel $pembayaranViewModel,
        private PaymentReviewServiceInterface $paymentReviewService,
    ) {}

    public function index(AdminPaymentIndexRequest $request): View
    {
        return view('admin.pembayaran.index', $this->pembayaranViewModel->getIndexData($request->validated()));
    }

    public function show(int $id): View
    {
        return view('admin.pembayaran.show', ['payment' => $this->pembayaranViewModel->find($id)]);
    }

    public function verify(Request $request, int $id): RedirectResponse
    {
        $result = $this->paymentReviewService->verify($id, (int) $request->user()->getKey());
        if ($result['error']) {
            return back()->withErrors(['payment' => $result['error']]);
        }
        $payment = $result['payment'];
        $routePrefix = $request->user()->isSuperAdmin() ? 'superadmin.pembayaran' : 'admin.pembayaran';

        return redirect()->route($routePrefix.'.show', $payment->id_pembayaran)->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject(RejectPaymentRequest $request, int $id): RedirectResponse
    {
        $payment = $this->paymentReviewService->reject(
            $id,
            $request->validated('alasan_penolakan'),
            (int) $request->user()->getKey(),
        );
        $routePrefix = $request->user()->isSuperAdmin() ? 'superadmin.pembayaran' : 'admin.pembayaran';

        return redirect()->route($routePrefix.'.show', $payment->id_pembayaran)->with('success', 'Pembayaran ditolak dan alasannya telah disimpan.');
    }
}
