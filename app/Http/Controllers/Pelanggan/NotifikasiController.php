<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pelanggan\NotificationIndexRequest;
use App\Models\User;
use App\Notifications\CustomerActivityNotification;
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
        $data = $this->notifikasiViewModel->getIndexData(
            $request->user(),
            $request->validated(),
        );

        if ($request->user()->isAdmin() || $request->user()->isSuperAdmin()) {
            $data['canManageNotifications'] = true;
            $data['storeNotificationRoute'] = $request->user()->isSuperAdmin()
                ? 'superadmin.notifikasi.store'
                : 'admin.notifikasi.store';
            $data['customers'] = User::query()
                ->where('role', 'pelanggan')
                ->orderBy('nama')
                ->get(['id_user', 'nama', 'email']);
        }

        return view('pelanggan.notifikasi.index', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->isAdmin() || $request->user()?->isSuperAdmin(), 403);

        $validated = $request->validate([
            'recipient' => ['required', 'in:all,specific'],
            'customer_id' => ['required_if:recipient,specific', 'nullable', 'integer'],
            'title' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $notification = new CustomerActivityNotification(
            'informasi',
            trim($validated['title']),
            trim($validated['message']),
            null,
            'Baca informasi',
        );

        if ($validated['recipient'] === 'specific') {
            $customer = User::query()
                ->where('role', 'pelanggan')
                ->whereKey($validated['customer_id'])
                ->firstOrFail();
            $customer->notify($notification);
            $sentCount = 1;
        } else {
            $customers = User::query()->where('role', 'pelanggan')->get();
            \Illuminate\Support\Facades\Notification::send($customers, $notification);
            $sentCount = $customers->count();
        }

        return back()->with('success', "Notifikasi berhasil dikirim kepada {$sentCount} pelanggan.");
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
