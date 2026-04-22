<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display user's orders.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.book')
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    /**
     * Display order details.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized.');
        }

        $order->load(['orderItems.book']);

        return view('user.orders.show', compact('order'));
    }

    /**
     * Checkout - create order from cart.
     */
    public function checkout(Request $request)
    {
        $carts = Cart::where('user_id', Auth::id())->with('book')->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        // Validate stock
        foreach ($carts as $cart) {
            if ($cart->book->stock < $cart->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok buku '{$cart->book->title}' tidak mencukupi.");
            }
        }

        // Calculate total
        $totalAmount = $carts->sum(function ($cart) {
            return $cart->book->price * $cart->quantity;
        });

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $totalAmount,
            'status' => \App\Models\Order::STATUS_PENDING,
        ]);

        // Create order items and decrease stock
        foreach ($carts as $cart) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $cart->book_id,
                'quantity' => $cart->quantity,
                'price_at_purchase' => $cart->book->price,
            ]);

            // Decrease stock
            $cart->book->decrement('stock', $cart->quantity);
        }

        // Clear cart
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Pesanan berhasil dibuat.');
    }

    /**
     * Cancel order (only pending orders).
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized.');
        }

        if ($order->status !== \App\Models\Order::STATUS_PENDING) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        // Restore stock
        foreach ($order->orderItems as $item) {
            $item->book->increment('stock', $item->quantity);
        }

        $order->update(['status' => \App\Models\Order::STATUS_CANCELLED]);

        return redirect()->route('orders.index')->with('success', 'Pesanan dibatalkan.');
    }
}