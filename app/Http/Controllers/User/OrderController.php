<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\UserAddress;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private \App\Services\OrderService $orderService,
        private \App\Repositories\Contracts\OrderRepositoryInterface $orderRepository
    ) {
    }
    /**
     * Display user's orders.
     */
    public function index(Request $request)
    {
        $orders = $this->orderRepository->getUserOrders(Auth::id(), [
            'status' => $request->status ?? $request->tab,
            'search' => $request->search,
            'per_page' => 10
        ])->withQueryString();

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

        $order->load([
            'orderItems.book',
            'latestPayment',
            'paymentHistories' => function ($q) {
                $q->latest();
            }
        ]);

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
        try {
            $order = $this->orderService->createOrder(
                Auth::id(),
                $request->cart_ids,
                $request->address_id
            );

            return redirect()->route('orders.show', $order->order_number)
                ->with('success', 'Pesanan berhasil dibuat. Silahkan lakukan pembayaran.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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