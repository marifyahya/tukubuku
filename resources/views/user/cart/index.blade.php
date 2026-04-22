@extends('layouts.app')

@section('title', 'Keranjang Belanja - TukuBuku')

@section('content')
<div class="bg-gray-50/50 py-8 min-h-[calc(100vh-200px)]">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-xl">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Keranjang Belanja</h1>
                <p class="text-gray-500 text-sm mt-1">Periksa kembali buku yang ingin Anda beli.</p>
            </div>
        </div>

        @if($carts->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center max-w-2xl mx-auto">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-cart text-gray-300 text-4xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">Keranjang Anda masih kosong</h2>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Yuk, cari buku favoritmu dan tambahkan ke keranjang sekarang!</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-xl hover:bg-primary/90 font-bold shadow-lg shadow-primary/30 transition-all transform active:scale-95">
                    <i class="fas fa-search"></i> Mulai Belanja
                </a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cart Items -->
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-50/80 border-b border-gray-100 text-sm font-semibold text-gray-500">
                            <div class="col-span-6">Produk</div>
                            <div class="col-span-2 text-center">Harga</div>
                            <div class="col-span-2 text-center">Jumlah</div>
                            <div class="col-span-2 text-right">Subtotal</div>
                        </div>

                        <div class="divide-y divide-gray-100">
                            @foreach($carts as $cart)
                                <div class="p-6 flex flex-col md:grid md:grid-cols-12 md:items-center gap-4 hover:bg-gray-50/50 transition-colors">
                                    <div class="md:col-span-6 flex items-start gap-4">
                                        @if($cart->book->cover_image)
                                            <img src="{{ Storage::url($cart->book->cover_image) }}" alt="{{ $cart->book->title }}" class="w-20 h-28 object-cover rounded-xl shadow-sm border border-gray-100">
                                        @else
                                            <div class="w-20 h-28 bg-gray-100 rounded-xl flex items-center justify-center border border-gray-200">
                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h3 class="font-bold text-gray-900 line-clamp-2 mb-1">{{ $cart->book->title }}</h3>
                                            <p class="text-sm text-gray-500 mb-2">{{ $cart->book->author }}</p>
                                            
                                            <!-- Mobile view only: Harga -->
                                            <p class="text-primary font-bold md:hidden">@rupiah($cart->book->price)</p>
                                            
                                            <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" class="mt-2 md:mt-4">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1.5 transition-colors">
                                                    <i class="far fa-trash-alt"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="hidden md:block md:col-span-2 text-center font-bold text-gray-800">
                                        @rupiah($cart->book->price)
                                    </div>
                                    
                                    <div class="md:col-span-2 flex items-center justify-between md:justify-center mt-2 md:mt-0">
                                        <span class="md:hidden text-sm text-gray-500 font-medium">Jumlah:</span>
                                        <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="flex items-center bg-gray-50 border border-gray-200 rounded-lg p-1">
                                            @csrf
                                            @method('PUT')
                                            <!-- Simple UI trick to auto-submit on change, but here we keep the button for simplicity or rely on JS if we had it. Let's make it a nice input group -->
                                            <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" max="{{ $cart->book->stock }}"
                                                class="w-12 bg-transparent text-center text-sm font-bold focus:outline-none" onchange="this.form.submit()">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-primary hover:bg-primary hover:text-white rounded-md transition-colors" title="Update">
                                                <i class="fas fa-sync-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <div class="md:col-span-2 flex items-center justify-between md:justify-end mt-2 md:mt-0">
                                        <span class="md:hidden text-sm text-gray-500 font-medium">Subtotal:</span>
                                        <span class="font-black text-gray-900">@rupiah($cart->book->price * $cart->quantity)</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 pb-4 border-b border-gray-100">Ringkasan Belanja</h2>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center justify-between text-gray-600">
                                <span>Total Item ({{ $carts->sum('quantity') }} barang)</span>
                                <span class="font-medium">@rupiah($total)</span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-4 mb-6">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-800 font-bold">Total Pembayaran</span>
                                <span class="text-2xl font-black text-primary">@rupiah($total)</span>
                            </div>
                        </div>

                        <form action="{{ route('checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-primary text-white py-3.5 rounded-xl font-bold text-lg shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                                <i class="fas fa-lock text-sm opacity-80"></i> Checkout Sekarang
                            </button>
                        </form>
                        
                        <p class="text-center text-xs text-gray-400 mt-4"><i class="fas fa-shield-alt mr-1"></i> Pembayaran aman dan terenkripsi</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection