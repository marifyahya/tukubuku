<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private \App\Services\PaymentService $paymentService
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

        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($order->status === OrderStatus::CANCELLED || ($order->status === OrderStatus::UNPAID && $order->created_at->addMinutes(30)->isPast())) {
            return response()->json(['message' => 'Order has expired. Please create a new order.'], 400);
        }

        try {
            $result = $this->paymentService->getPaymentToken($order, $request->change_method ?? false);
            
            return response()->json([
                'snap_token' => $result['snap_token'],
                'order_id' => $order->id,
                'expiry_at' => $result['expiry_at']->toIso8601String()
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
        if ($this->paymentService->processNotification()) {
            return response()->json(['message' => 'OK']);
        }

        return response()->json(['message' => 'Notification processed or record not found']);
    }
}
