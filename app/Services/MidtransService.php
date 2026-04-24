<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        $this->setup();
    }

    /**
     * Initialize Midtrans configuration.
     */
    private function setup(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create a Snap Token for an order.
     *
     * @param Order $order
     * @param int $attemptCount
     * @param int $expiryMinutes
     * @return string
     * @throws \Exception
     */
    public function createSnapToken(Order $order, int $attemptCount, int $expiryMinutes = 45): string
    {
        $midtransOrderId = "{$order->order_number}-{$attemptCount}";
        $grossAmount = (int)($order->total_amount + $order->shipping_cost);

        $itemDetails = [];
        foreach ($order->orderItems as $item) {
            $itemDetails[] = [
                'id' => $item->book_id,
                'price' => (int)$item->price_at_purchase,
                'quantity' => $item->quantity,
                'name' => substr($item->book->title, 0, 50),
            ];
        }

        $itemDetails[] = [
            'id' => 'shipping',
            'price' => (int)$order->shipping_cost,
            'quantity' => 1,
            'name' => 'Ongkos Kirim',
        ];

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'minutes',
                'duration' => $expiryMinutes
            ]
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * Cancel a transaction in Midtrans.
     *
     * @param string $midtransOrderId
     * @return bool
     * @throws \Exception
     */
    public function cancelTransaction(string $midtransOrderId): bool
    {
        $response = Transaction::cancel($midtransOrderId);
        return in_array($response, ['200', '201', 'success']);
    }

    /**
     * Get Midtrans Notification object.
     *
     * @return Notification
     * @throws \Exception
     */
    public function getNotification(): Notification
    {
        return new Notification();
    }

    /**
     * Get transaction status directly from Midtrans API.
     */
    public function getTransactionStatus(string $midtransOrderId): object
    {
        return Transaction::status($midtransOrderId);
    }
}
