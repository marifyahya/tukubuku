<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display user's cart.
     */
    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->with('book')->get();
        $total = $carts->sum(function ($cart) {
            return $cart->book->price * $cart->quantity;
        });

        return view('user.cart.index', compact('carts', 'total'));
    }

    /**
     * Add book to cart.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->stock < $validated['quantity']) {
            return back()->with('error', 'Stok buku tidak mencukupi.');
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('book_id', $validated['book_id'])
            ->first();

        if ($cart) {
            $newQuantity = $cart->quantity + $validated['quantity'];
            if ($newQuantity > $book->stock) {
                return back()->with('error', 'Stok buku tidak mencukupi.');
            }
            $cart->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'book_id' => $validated['book_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Buku ditambahkan ke keranjang.');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized.');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validated['quantity'] > $cart->book->stock) {
            return back()->with('error', 'Stok buku tidak mencukupi.');
        }

        $cart->update(['quantity' => $validated['quantity']]);

        return redirect()->route('cart.index')->with('success', 'Keranjang diperbarui.');
    }

    /**
     * Remove book from cart.
     */
    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized.');
        }

        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Buku dihapus dari keranjang.');
    }
}
