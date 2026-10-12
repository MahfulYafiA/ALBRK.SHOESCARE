<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pelanggan\NotificationIndexRequest;
use App\Services\Contracts\NotificationServiceInterface;
use App\ViewModels\Pelanggan\NotifikasiViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    public function __construct(
        private NotifikasiViewModel $notifikasiViewModel,
        private NotificationServiceInterface $notificationService,
    ) {}

    public function index(NotificationIndexRequest $request): View
    {
        return view('pelanggan.notifikasi.index', $this->notifikasiViewModel->getIndexData(
            $request->user(),
            $request->validated(),
        ));
    }

    public function show(Request $request, string $notification): View|RedirectResponse
    {
        $data = $this->notifikasiViewModel->getShowData($request->user(), $notification);
        if (isset($data['redirect_route'])) {
            return redirect()->route($data['redirect_route'], $data['redirect_parameters'])
                ->with('success', 'Notifikasi telah ditandai sebagai sudah dibaca.');
        }

        return view('pelanggan.notifikasi.show', $data);
    }

    public function markRead(Request $request, string $notification): RedirectResponse
    {
        $this->notificationService->markRead($request->user(), $notification);

        return back()->with('success', 'Notifikasi telah ditandai sebagai sudah dibaca.');
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $this->notificationService->markAllRead($request->user());

        return back()->with('success', 'Notifikasi telah ditandai sebagai sudah dibaca.');
    }
}
