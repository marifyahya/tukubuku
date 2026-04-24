<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display user's orders.
     */
    public function index(Request $request)
    {
        $query = Order::where('user_id', Auth::id())
            ->with(['orderItems.book', 'latestPayment'])
            ->latest();

        if ($request->has('tab') && $request->tab !== '') {
            $query->where('status', $request->tab);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('orderItems.book', function($bq) use ($search) {
                      $bq->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('user.orders.index', compact('orders'));
    }

    /**
     * Display order details.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'Unauthorized.');
        }

        $order->load(['orderItems.book', 'latestPayment', 'paymentHistories' => function($q) {
            $q->latest();
        }]);

        return view('user.orders.show', compact('order'));
    }

    /**
     * Show checkout confirmation page.
     */
    public function showConfirmation(Request $request)
    {
        if (!$request->has('cart_ids') || !is_array($request->cart_ids)) {
            return redirect()->route('cart.index')->with('error', 'Pilih item yang ingin dibeli.');
        }

        $carts = Cart::where('user_id', Auth::id())
            ->whereIn('id', $request->cart_ids)
            ->with('book')
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Item tidak ditemukan.');
        }

        $addresses = UserAddress::where('user_id', Auth::id())->get();
        
        $subtotal = $carts->sum(function ($cart) {
            return $cart->book->price * $cart->quantity;
        });

        $shippingCost = config('midtrans.shipping_cost', 16000);
        $total = $subtotal + $shippingCost;

        return view('user.checkout.confirm', compact('carts', 'addresses', 'subtotal', 'shippingCost', 'total'));
    }

    /**
     * Checkout - create order from selected cart items.
     */
    public function checkout(CheckoutRequest $request)
    {
        $carts = Cart::where('user_id', Auth::id())
            ->whereIn('id', $request->cart_ids)
            ->with('book')
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tidak ada item yang dipilih.');
        }

        // Validate address
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($request->address_id);

        // Validate stock
        foreach ($carts as $cart) {
            if ($cart->book->stock < $cart->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok buku '{$cart->book->title}' tidak mencukupi.");
            }
        }

        // Calculate total
        $subtotal = $carts->sum(function ($cart) {
            return $cart->book->price * $cart->quantity;
        });

        $shippingCost = config('midtrans.shipping_cost', 16000);

        // Create Address Snapshot
        $addressSnapshot = sprintf(
            "%s | %s\n%s%s",
            $address->full_name,
            $address->phone_number,
            $address->full_address,
            $address->landmark ? " ({$address->landmark})" : ""
        );

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'shipping_address' => $addressSnapshot,
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

            // Decrease stock
            $cart->book->decrement('stock', $cart->quantity);
        }

        // Remove only checked-out items from cart
        Cart::where('user_id', Auth::id())
            ->whereIn('id', $request->cart_ids)
            ->delete();

        // Dispatch Job for automatic cancellation after 30 minutes
        \App\Jobs\CancelOrderJob::dispatch($order)->delay(now()->addMinutes(30));

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Pesanan berhasil dibuat. Silahkan lakukan pembayaran.');
    }

    /**
     * Cancel order (manual by user).
     */
    public function cancel(Order $order, \App\Services\OrderService $orderService)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized.');
        }

        if ($orderService->cancelOrder($order)) {
            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return back()->with('error', 'Pesanan tidak dapat dibatalkan atau sudah diproses.');
    }
}