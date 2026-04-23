<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.book']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the specified order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', \Illuminate\Validation\Rule::enum(OrderStatus::class)],
        ]);

        $oldStatus = $order->status;
        $newStatus = OrderStatus::from($validated['status']);

        $order->update(['status' => $newStatus]);

        if ($oldStatus !== OrderStatus::COMPLETED && $newStatus === OrderStatus::COMPLETED) {
            foreach ($order->orderItems as $item) {
                $item->book->increment('sold_count', $item->quantity);
            }
        } elseif ($oldStatus === OrderStatus::COMPLETED && $newStatus !== OrderStatus::COMPLETED) {
            foreach ($order->orderItems as $item) {
                $item->book->decrement('sold_count', $item->quantity);
            }
        }

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Order status updated successfully.');
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
