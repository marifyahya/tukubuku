<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderPaymentHistory;
use App\Enums\OrderStatus;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private MidtransService $midtransService
    ) {
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

        // Check if order is already cancelled or expired (30 minutes rule)
        if ($order->status === OrderStatus::CANCELLED || ($order->status === OrderStatus::UNPAID && $order->created_at->addMinutes(30)->isPast())) {
            return response()->json(['message' => 'Order has expired. Please create a new order.'], 400);
        }

        // Check latest payment
        $latestPayment = $order->latestPayment;

        // If not change_method and we have an active token that isn't expired
        if (!$request->change_method && $latestPayment && $latestPayment->payment_status === OrderPaymentHistory::STATUS_PENDING) {
            if ($latestPayment->expiry_at && $latestPayment->expiry_at->isFuture()) {
                return response()->json([
                    'snap_token' => $latestPayment->payment_token,
                    'order_id' => $order->id,
                    'expiry_at' => $latestPayment->expiry_at->toIso8601String()
                ]);
            }
        }

        // Cancel previous transaction in Midtrans if it was pending
        if ($latestPayment && $latestPayment->payment_status === OrderPaymentHistory::STATUS_PENDING) {
            try {
                $this->midtransService->cancelTransaction($latestPayment->midtrans_order_id);
                $latestPayment->update(['payment_status' => OrderPaymentHistory::STATUS_CANCEL]);
            } catch (\Exception $e) {
                // Ignore if fails
            }
        }

        $expiryMinutes = 45; // 45 minutes as requested
        try {
            $snapToken = $this->midtransService->createSnapToken($order, $order->paymentHistories->count() + 1, $expiryMinutes);
            
            $payment = $order->paymentHistories()->create([
                'midtrans_order_id' => "{$order->order_number}-" . ($order->paymentHistories->count() + 1),
                'payment_token' => $snapToken,
                'payment_status' => OrderPaymentHistory::STATUS_PENDING,
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
            $notif = $this->midtransService->getNotification();
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
                    $payment->payment_status = OrderPaymentHistory::STATUS_CHALLENGE;
                } else {
                    $payment->payment_status = OrderPaymentHistory::STATUS_SETTLEMENT;
                    $payment->paid_at = now();
                    $order->update(['status' => OrderStatus::PACKING]);
                }
            }
        } else if ($transaction == 'settlement') {
            $payment->payment_status = OrderPaymentHistory::STATUS_SETTLEMENT;
            $payment->paid_at = now();
            $order->update(['status' => OrderStatus::PACKING]);
        } else if ($transaction == 'pending') {
            $payment->payment_status = OrderPaymentHistory::STATUS_PENDING;
        } else if ($transaction == 'deny') {
            $payment->payment_status = OrderPaymentHistory::STATUS_DENY;
        } else if ($transaction == 'expire') {
            $payment->payment_status = OrderPaymentHistory::STATUS_EXPIRE;
        } else if ($transaction == 'cancel') {
            $payment->payment_status = OrderPaymentHistory::STATUS_CANCEL;
        }

        $payment->payment_method = $type;
        $payment->payload = $request->all();
        $payment->save();

        return response()->json(['message' => 'OK']);
    }
}
