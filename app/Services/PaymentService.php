<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderPaymentHistory;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        private MidtransService $midtransService
    ) {
    }

    /**
     * Get Snap Token for an order, handling previous pending transactions.
     */
    public function getPaymentToken(Order $order, bool $changeMethod = false): array
    {
        $latestPayment = $order->latestPayment;

        // Calculate remaining minutes until order expires (30 minutes from creation)
        $orderExpiry = $order->created_at->addMinutes(30);
        $remainingMinutes = (int) now()->diffInMinutes($orderExpiry, false);

        if ($remainingMinutes <= 0) {
            throw new \Exception("Pesanan ini telah kedaluwarsa dan tidak dapat dibayar.");
        }

        // 1. Reuse existing token if not changing method and still valid
        if (!$changeMethod && $latestPayment && $latestPayment->payment_status === OrderPaymentHistory::STATUS_PENDING) {
            if ($latestPayment->expiry_at && $latestPayment->expiry_at->isFuture()) {
                return [
                    'snap_token' => $latestPayment->payment_token,
                    'expiry_at' => $latestPayment->expiry_at
                ];
            }
        }

        // 2. Cancel previous pending transaction in Midtrans
        if ($latestPayment && $latestPayment->payment_status === OrderPaymentHistory::STATUS_PENDING) {
            try {
                $this->midtransService->cancelTransaction($latestPayment->midtrans_order_id);
                $latestPayment->update(['payment_status' => OrderPaymentHistory::STATUS_CANCEL]);
            } catch (\Exception $e) {
                // Fail silently
            }
        }

        // 3. Create new Snap Token with remaining time
        $attemptCount = $order->paymentHistories()->count() + 1;
        $snapToken = $this->midtransService->createSnapToken($order, $attemptCount, $remainingMinutes);

        $payment = $order->paymentHistories()->create([
            'midtrans_order_id' => "{$order->order_number}-{$attemptCount}",
            'payment_token' => $snapToken,
            'payment_status' => OrderPaymentHistory::STATUS_PENDING,
            'gross_amount' => $order->total_amount + $order->shipping_cost,
            'expiry_at' => $orderExpiry, // Sync with order expiry
        ]);

        return [
            'snap_token' => $snapToken,
            'expiry_at' => $payment->expiry_at
        ];
    }

    /**
     * Handle Midtrans Webhook Notification.
     */
    public function processNotification(): bool
    {
        $notif = $this->midtransService->getNotification();

        // 1. Security: Validate Signature Key
        if (!$this->midtransService->validateSignature($notif)) {
            Log::warning("Midtrans Webhook: Invalid Signature Key for Order ID: {$notif->order_id}");
            return false;
        }

        return $this->updatePaymentFromMidtrans($notif->order_id, $notif);
    }

    /**
     * Sync payment status manually from Midtrans API.
     */
    public function syncStatus(string $midtransOrderId): bool
    {
        try {
            $statusResponse = $this->midtransService->getTransactionStatus($midtransOrderId);
            return $this->updatePaymentFromMidtrans($midtransOrderId, $statusResponse);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update payment and order status based on Midtrans response object/notification.
     */
    private function updatePaymentFromMidtrans(string $midtransOrderId, object $response): bool
    {
        $payment = OrderPaymentHistory::where('midtrans_order_id', $midtransOrderId)->first();
        if (!$payment) {
            return false;
        }

        $order = $payment->order;
        $status = $this->mapMidtransStatus(
            $response->transaction_status,
            $response->fraud_status ?? 'accept',
            $response->payment_type
        );

        // 2. Idempotency: Skip if payment is already in a final state
        if (in_array($payment->payment_status, [OrderPaymentHistory::STATUS_SETTLEMENT, OrderPaymentHistory::STATUS_CAPTURE])) {
            // Already paid, but we still trigger event for frontend sync just in case
            \App\Events\PaymentStatusUpdated::dispatch($order);
            return true;
        }

        DB::transaction(function () use ($payment, $order, $status, $response) {
            $payment->update([
                'payment_status' => $status,
                'payment_method' => $response->payment_type,
                'payload' => (array) $response, // Cast to array for JSON column
                'paid_at' => in_array($status, [OrderPaymentHistory::STATUS_SETTLEMENT, OrderPaymentHistory::STATUS_CAPTURE]) ? now() : $payment->paid_at
            ]);

            if ($status === OrderPaymentHistory::STATUS_SETTLEMENT || $status === OrderPaymentHistory::STATUS_CAPTURE) {
                $order->update(['status' => OrderStatus::PACKING]);
            }
        });

        \App\Events\PaymentStatusUpdated::dispatch($order);

        return true;
    }

    /**
     * Map Midtrans transaction status to our internal status.
     */
    private function mapMidtransStatus(string $transaction, string $fraud, string $type): string
    {
        return match ($transaction) {
            'capture' => ($type == 'credit_card' && $fraud == 'challenge')
            ? OrderPaymentHistory::STATUS_CHALLENGE
            : OrderPaymentHistory::STATUS_SETTLEMENT,
            'settlement' => OrderPaymentHistory::STATUS_SETTLEMENT,
            'pending' => OrderPaymentHistory::STATUS_PENDING,
            'deny' => OrderPaymentHistory::STATUS_DENY,
            'expire' => OrderPaymentHistory::STATUS_EXPIRE,
            'cancel' => OrderPaymentHistory::STATUS_CANCEL,
            default => OrderPaymentHistory::STATUS_PENDING,
        };
    }
}
