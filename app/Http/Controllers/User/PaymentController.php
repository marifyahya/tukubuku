<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderPaymentHistory;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Get Midtrans Snap Token for an order.
     */
    public function getSnapToken(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'change_method' => 'nullable|boolean',
        ]);

        $order = Order::with(['user', 'orderItems.book', 'latestPayment', 'paymentHistories'])->findOrFail($request->order_id);

        // Security check
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check latest payment
        $latestPayment = $order->latestPayment;

        // If not change_method and we have an active token that isn't expired
        if (!$request->change_method && $latestPayment && $latestPayment->payment_status === 'pending') {
            if ($latestPayment->expiry_at && $latestPayment->expiry_at->isFuture()) {
                return response()->json([
                    'snap_token' => $latestPayment->payment_token,
                    'order_id' => $order->id,
                    'expiry_at' => $latestPayment->expiry_at->toIso8601String()
                ]);
            }
        }

        // Cancel previous transaction in Midtrans if it was pending
        if ($latestPayment && $latestPayment->payment_status === 'pending') {
            try {
                \Midtrans\Transaction::cancel($latestPayment->midtrans_order_id);
                $latestPayment->update(['payment_status' => 'cancelled']);
            } catch (\Exception $e) {
                // Ignore if fails
            }
        }

        $attemptCount = $order->paymentHistories->count() + 1;
        $midtransOrderId = "{$order->order_number}-{$attemptCount}";
        $grossAmount = (int)($order->total_amount + $order->shipping_cost);

        $item_details = [];
        foreach ($order->orderItems as $item) {
            $item_details[] = [
                'id' => $item->book_id,
                'price' => (int)$item->price_at_purchase,
                'quantity' => $item->quantity,
                'name' => substr($item->book->title, 0, 50),
            ];
        }

        $item_details[] = [
            'id' => 'shipping',
            'price' => (int)$order->shipping_cost,
            'quantity' => 1,
            'name' => 'Ongkos Kirim',
        ];

        $expiryMinutes = 60; // 1 hour as requested
        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $item_details,
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

        try {
            $snapToken = Snap::getSnapToken($params);
            
            $payment = $order->paymentHistories()->create([
                'midtrans_order_id' => $midtransOrderId,
                'payment_token' => $snapToken,
                'payment_status' => 'pending',
                'gross_amount' => $order->total_amount + $order->shipping_cost,
                'expiry_at' => now()->addMinutes($expiryMinutes),
            ]);
            
            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $order->id,
                'expiry_at' => $payment->expiry_at->toIso8601String()
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle Midtrans Webhook Notification.
     */
    public function notification(Request $request)
    {
        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $midtrans_order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        $payment = OrderPaymentHistory::where('midtrans_order_id', $midtrans_order_id)->first();
        
        if (!$payment) {
            return response()->json(['message' => 'Payment record not found'], 404);
        }

        $order = $payment->order;

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $payment->payment_status = 'challenge';
                } else {
                    $payment->payment_status = 'settlement';
                    $payment->paid_at = now();
                    $order->update(['status' => OrderStatus::PACKING]);
                }
            }
        } else if ($transaction == 'settlement') {
            $payment->payment_status = 'settlement';
            $payment->paid_at = now();
            $order->update(['status' => OrderStatus::PACKING]);
        } else if ($transaction == 'pending') {
            $payment->payment_status = 'pending';
        } else if ($transaction == 'deny') {
            $payment->payment_status = 'deny';
        } else if ($transaction == 'expire') {
            $payment->payment_status = 'expire';
        } else if ($transaction == 'cancel') {
            $payment->payment_status = 'cancel';
        }

        $payment->payment_method = $type;
        $payment->payload = $request->all();
        $payment->save();

        return response()->json(['message' => 'OK']);
    }
}
