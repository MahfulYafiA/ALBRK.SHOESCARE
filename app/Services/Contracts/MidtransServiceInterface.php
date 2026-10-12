<?php

namespace App\Services\Contracts;

interface MidtransServiceInterface
{
    /**
     * Get Snap Token for payment
     */
    public function getSnapToken(int $reservasiId): ?string;

    /**
     * Process callback from Midtrans
     */
    public function processCallback(array $notification): array;

    public function hasValidCallbackSignature(array $notification): bool;

    /**
     * Check transaction status
     */
    public function checkTransaction(string $orderId): ?array;
}
