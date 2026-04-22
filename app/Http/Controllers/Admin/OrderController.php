<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
            'status' => 'required|integer|in:0,1,2,3',
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        $order->update(['status' => $newStatus]);

        if ($oldStatus != Order::STATUS_COMPLETED && $newStatus == Order::STATUS_COMPLETED) {
            foreach ($order->orderItems as $item) {
                $item->book->increment('sold_count', $item->quantity);
            }
        } elseif ($oldStatus == Order::STATUS_COMPLETED && $newStatus != Order::STATUS_COMPLETED) {
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
