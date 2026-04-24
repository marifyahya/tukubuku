<?php

namespace App\Services;

use App\Models\OrderPaymentHistory;
use App\Enums\OrderStatus;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private MidtransService $midtransService
    ) {
    }

    /**
     * Cancel orders that have expired and restore stock.
     *
     * @param int $minutes
     * @return array List of cancelled order numbers
     */
    public function cancelExpiredOrders(int $minutes = 30): array
    {
        $expiredOrders = $this->orderRepository->getExpiredUnpaidOrders($minutes);
        $cancelledOrderNumbers = [];

        foreach ($expiredOrders as $order) {
            if ($this->cancelOrder($order)) {
                $cancelledOrderNumbers[] = $order->order_number;
            }
        }

        return $cancelledOrderNumbers;
    }

    /**
     * Cancel a single order if it is still unpaid.
     *
     * @param \App\Models\Order $order
     * @return bool
     */
    public function cancelOrder($order): bool
    {
        // Re-check status to prevent double cancellation
        if ($order->status !== OrderStatus::UNPAID) {
            return false;
        }

        try {
            DB::transaction(function () use ($order) {
                // 1. Restore Stock
                foreach ($order->orderItems as $item) {
                    $item->book()->increment('stock', $item->quantity);
                }

                // 2. Update Order Status
                $this->orderRepository->updateStatus($order->id, OrderStatus::CANCELLED);

                // 3. Sync Midtrans (Cancel transaction)
                $this->cancelMidtransTransaction($order);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to cancel order {$order->order_number}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Attempt to cancel the transaction in Midtrans.
     *
     * @param \App\Models\Order $order
     */
    private function cancelMidtransTransaction($order): void
    {
        try {
            // Get the pending payment and cancel it in Midtrans
            $latestPayment = $order->paymentHistories()
                ->where('payment_status', OrderPaymentHistory::STATUS_PENDING)
                ->first();

            if ($latestPayment) {
                $this->midtransService->cancelTransaction($latestPayment->midtrans_order_id);
                
                // Update status directly via query builder to avoid stdClass issues
                $order->paymentHistories()
                    ->where('id', $latestPayment->id)
                    ->update(['payment_status' => OrderPaymentHistory::STATUS_CANCEL]);
            }
        } catch (\Exception $e) {
            Log::warning("Midtrans cancel failed for order {$order->order_number}: " . $e->getMessage());
        }
    }
}
