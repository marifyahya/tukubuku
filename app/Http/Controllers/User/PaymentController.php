<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
        ]);

        $order = Order::with(['user', 'orderItems.book'])->findOrFail($request->order_id);

        // Security check
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Return existing token if already paid or failed
        if ($order->payment_status !== 'pending') {
            return response()->json(['message' => 'Order is already ' . $order->payment_status], 400);
        }

        // If we already have a token, we might want to reuse it, 
        // but Midtrans tokens expire, so let's generate a new one if requested.
        
        $item_details = [];
        foreach ($order->orderItems as $item) {
            $item_details[] = [
                'id' => $item->book_id,
                'price' => (int)$item->price_at_purchase,
                'quantity' => $item->quantity,
                'name' => substr($item->book->title, 0, 50),
            ];
        }

        // Add shipping cost as an item
        $item_details[] = [
            'id' => 'shipping',
            'price' => (int)$order->shipping_cost,
            'quantity' => 1,
            'name' => 'Ongkos Kirim',
        ];

        $params = [
            'transaction_details' => [
                'order_id' => $order->id . '-' . time(), // Unique order id for Midtrans
                'gross_amount' => (int)($order->total_amount + $order->shipping_cost),
            ],
            'item_details' => $item_details,
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $order->update(['payment_token' => $snapToken]);
            
            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $order->id
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
        $order_id_raw = $notif->order_id;
        $fraud = $notif->fraud_status;

        // Split order_id if we added timestamp
        $order_id = explode('-', $order_id_raw)[0];
        $order = Order::findOrFail($order_id);

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $order->payment_status = 'challenge';
                } else {
                    $order->payment_status = 'paid';
                    $order->status = OrderStatus::PACKING;
                    $order->paid_at = now();
                }
            }
        } else if ($transaction == 'settlement') {
            $order->payment_status = 'paid';
            $order->status = OrderStatus::PACKING;
            $order->paid_at = now();
        } else if ($transaction == 'pending') {
            $order->payment_status = 'pending';
        } else if ($transaction == 'deny') {
            $order->payment_status = 'failed';
        } else if ($transaction == 'expire') {
            $order->payment_status = 'failed';
        } else if ($transaction == 'cancel') {
            $order->payment_status = 'failed';
        }

        $order->payment_method = $type;
        $order->save();

        return response()->json(['message' => 'OK']);
    }
}
