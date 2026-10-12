<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservasi\StoreReservasiRequest;
use App\Http\Requests\Reservasi\UpdatePengembalianRequest;
use App\Services\Contracts\MidtransServiceInterface;
use App\Services\Contracts\ReservasiServiceInterface;
use App\ViewModels\Pelanggan\DashboardViewModel;
use App\ViewModels\Pelanggan\ReservasiViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservasiController extends Controller
{
    public function __construct(
        private DashboardViewModel $dashboardViewModel,
        private ReservasiViewModel $reservasiViewModel,
        private ReservasiServiceInterface $reservasiService,
        private MidtransServiceInterface $midtransService,
    ) {}

    /**
     * Show dashboard
     */
    public function dashboard(Request $request): View|RedirectResponse
    {
        if (auth()->user()?->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }

        if (auth()->user()?->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $data = $this->dashboardViewModel->getDashboardData((int) $request->user()->getKey());

        return view('pelanggan.dasbor', $data);
    }

    /**
     * Show create reservasi form
     */
    public function create(): View
    {
        $layanans = $this->reservasiViewModel->getLayanans();

        return view('pelanggan.reservasi.create', compact('layanans'));
    }

    /**
     * Store new reservasi
     */
    public function store(StoreReservasiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['id_user'] = (int) $request->user()->getKey();
        $result = $this->reservasiService->createReservasi($data);
        if (! $result['success'] || ! isset($result['reservasi']['id_reservasi'])) {
            return back()->with('error', $result['message'] ?? 'Gagal membuat reservasi.')->withInput();
        }

        if ($request->metode_pembayaran === 'Payment Gateway') {
            return redirect()->route('reservasi.pembayaran', $result['reservasi']['id_reservasi']);
        }

        return redirect()->route('pelanggan.riwayat-reservasi')->with('success', 'Reservasi berhasil dibuat!');
    }

    /**
     * Show payment page
     */
    public function pembayaran(int $id): View|RedirectResponse
    {
        $reservasi = $this->reservasiService->findReservasiForUser($id, (int) auth()->id());

        if (! $reservasi) {
            return redirect()->route('pelanggan.riwayat-reservasi')->with('error', 'Reservasi tidak ditemukan.');
        }

        $snapToken = $this->midtransService->getSnapToken($id);
        if (! $snapToken) {
            return redirect()->route('pelanggan.riwayat-reservasi')->with('error', 'Pembayaran Midtrans tidak tersedia. Periksa status reservasi atau coba lagi nanti.');
        }

        return view('pelanggan.reservasi.pembayaran', compact('reservasi', 'snapToken'));
    }

    /**
     * Update return method
     */
    public function pilihPengembalian(UpdatePengembalianRequest $request, int $id): RedirectResponse
    {
        $result = $this->reservasiService->updateReturnForUser(
            $id,
            (int) $request->user()->getKey(),
            $request->validated(),
        );

        return $result['success']
            ? back()->with('success', 'Metode pengembalian berhasil diperbarui.')
            : back()->with('error', $result['message']);
    }

    /**
     * Cancel reservasi
     */
    public function cancel(int $id): RedirectResponse
    {
        $result = $this->reservasiService->cancelForUser($id, (int) auth()->id());

        return $result['success']
            ? redirect()->route('pelanggan.riwayat-reservasi')->with('success', $result['message'])
            : back()->with('error', $result['message']);
    }
}
