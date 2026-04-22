@extends('layouts.app')

@section('title', 'Keranjang - Toko Buku')

@section('content')
<h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

@if($carts->isEmpty())
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <p class="text-gray-600 mb-4">Keranjang Anda kosong.</p>
        <a href="{{ route('books.index') }}" class="text-indigo-600 hover:underline">Jelajahi Buku</a>
    </div>
@else
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left">Buku</th>
                    <th class="px-6 py-3 text-left">Harga</th>
                    <th class="px-6 py-3 text-left">Jumlah</th>
                    <th class="px-6 py-3 text-left">Subtotal</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($carts as $cart)
                    <tr class="border-t">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($cart->book->cover_image)
                                    <img src="{{ Storage::url($cart->book->cover_image) }}" alt="{{ $cart->book->title }}" class="w-16 h-20 object-cover rounded mr-4">
                                @else
                                    <div class="w-16 h-20 bg-gray-200 rounded mr-4 flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No Cover</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold">{{ $cart->book->title }}</p>
                                    <p class="text-sm text-gray-600">{{ $cart->book->author }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">@rupiah($cart->book->price)</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" max="{{ $cart->book->stock }}"
                                    class="w-16 px-2 py-1 border rounded text-center">
                                <button type="submit" class="text-indigo-600 hover:text-indigo-800 text-sm">Update</button>
                            </form>
                        </td>
                        <td class="px-6 py-4 font-semibold">@rupiah($cart->book->price * $cart->quantity)</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('cart.destroy', $cart->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-100">
                <tr>
                    <td colspan="3" class="px-6 py-4 text-right font-bold">Total:</td>
                    <td class="px-6 py-4 font-bold text-xl">@rupiah($total)</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        <form action="{{ route('checkout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-semibold text-lg">
                Checkout
            </button>
        </form>
    </div>
@endif
@endsection