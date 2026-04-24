<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\UserAddress;
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
    /**
     * Create a new order from cart items.
     */
    public function createOrder(int $userId, array $cartIds, int $addressId): Order
    {
        return DB::transaction(function () use ($userId, $cartIds, $addressId) {
            $carts = Cart::where('user_id', $userId)
                ->whereIn('id', $cartIds)
                ->with('book')
                ->get();

            if ($carts->isEmpty()) {
                throw new \Exception('Pilih item yang ingin dibeli.');
            }

            $address = UserAddress::where('user_id', $userId)->findOrFail($addressId);

            // Validate stock
            foreach ($carts as $cart) {
                if ($cart->book->stock < $cart->quantity) {
                    throw new \Exception("Stok buku '{$cart->book->title}' tidak mencukupi.");
                }
            }

            $subtotal = $carts->sum(function ($cart) {
                return $cart->book->price * $cart->quantity;
            });

            $shippingCost = config('midtrans.shipping_cost', 16000);

            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'shipping_address' => $this->formatAddress($address),
                'total_amount' => $subtotal,
                'shipping_cost' => $shippingCost,
                'status' => OrderStatus::UNPAID,
            ]);

            // Create order items and decrease stock
            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $cart->book_id,
                    'quantity' => $cart->quantity,
                    'price_at_purchase' => $cart->book->price,
                ]);

                $cart->book->decrement('stock', $cart->quantity);
            }

            // Remove items from cart
            Cart::where('user_id', $userId)->whereIn('id', $cartIds)->delete();

            // Dispatch Auto-Cancel Job
            \App\Jobs\CancelOrderJob::dispatch($order)->delay(now()->addMinutes(30));

            return $order;
        });
    }

    /**
     * Format address into a snapshot string.
     */
    private function formatAddress(UserAddress $address): string
    {
        return sprintf(
            "%s | %s\n%s%s",
            $address->full_name,
            $address->phone_number,
            $address->full_address,
            $address->landmark ? " ({$address->landmark})" : ""
        );
    }

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
