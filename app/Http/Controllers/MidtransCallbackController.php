<?php

namespace App\Http\Controllers;

use App\Http\Requests\MidtransCallbackRequest;
use App\Services\Contracts\MidtransServiceInterface;

class MidtransCallbackController extends Controller
{
    public function __construct(
        private MidtransServiceInterface $midtransService
    ) {}

    /**
     * Handle Midtrans callback/webhook
     */
    public function callback(MidtransCallbackRequest $request)
    {
        $payload = $request->validated();
        if (! $this->midtransService->hasValidCallbackSignature($payload)) {
            return response()->json(['success' => false, 'message' => 'Signature not valid'], 403);
        }

        $result = $this->midtransService->processCallback($payload);

        if (($result['success'] ?? false) === true) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => $result['message'] ?? 'Callback gagal diproses'], 400);
    }
}
