@extends('layouts.app')

@section('title', 'Konfirmasi Pesanan - TukuBuku')

@section('content')
<div class="bg-gray-50/50 py-8 min-h-[calc(100vh-200px)]">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('cart.index') }}" class="w-10 h-10 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center text-gray-400 hover:text-primary transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Konfirmasi Pesanan</h1>
                <p class="text-gray-500 text-sm mt-1">Selesaikan pembayaran untuk memesan buku Anda.</p>
            </div>
        </div>

        <form action="{{ route('checkout') }}" method="POST" id="main-checkout-form">
            @csrf
            @foreach($carts as $cart)
                <input type="hidden" name="cart_ids[]" value="{{ $cart->id }}">
            @endforeach

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Main Content -->
                <div class="lg:w-2/3 space-y-6">
                    <!-- Address Selection -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-primary"></i> Alamat Pengiriman
                            </h2>
                            <a href="{{ route('addresses.index') }}" class="text-primary text-sm font-bold hover:underline">
                                <i class="fas fa-plus mr-1"></i> Tambah Alamat
                            </a>
                        </div>

                        @if($addresses->isEmpty())
                            <div class="bg-rose-50 border border-rose-100 rounded-xl p-4 text-center">
                                <p class="text-rose-600 text-sm font-medium mb-3">Anda belum memiliki alamat pengiriman tersimpan.</p>
                                <a href="{{ route('addresses.index') }}" class="inline-flex bg-rose-500 text-white px-4 py-2 rounded-lg text-sm font-bold">
                                    Tambah Alamat Sekarang
                                </a>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($addresses as $address)
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="address_id" value="{{ $address->id }}" class="peer sr-only" {{ $address->is_primary ? 'checked' : '' }} required>
                                        <div class="h-full p-4 rounded-xl border-2 border-gray-100 peer-checked:border-primary peer-checked:bg-primary/5 transition-all hover:border-gray-200">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-bold text-gray-900">{{ $address->full_name }}</span>
                                                @if($address->is_primary)
                                                    <span class="text-[10px] bg-primary text-white px-2 py-0.5 rounded-full font-bold">Utama</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 mb-1 font-bold">{{ $address->phone_number }}</p>
                                            <p class="text-xs text-gray-600 line-clamp-2">{{ $address->full_address }}</p>
                                            @if($address->landmark)
                                                <p class="text-[10px] text-gray-400 mt-1 italic">Patokan: {{ $address->landmark }}</p>
                                            @endif
                                            
                                            <div class="absolute top-4 right-4 opacity-0 peer-checked:opacity-100 transition-opacity">
                                                <i class="fas fa-check-circle text-primary"></i>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Product Review -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100">
                            <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-book text-primary"></i> Review Produk
                            </h2>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($carts as $cart)
                                <div class="p-6 flex gap-4">
                                    @if($cart->book->cover_image)
                                        <img src="{{ Storage::url($cart->book->cover_image) }}" alt="{{ $cart->book->title }}" class="w-16 h-24 object-cover rounded-lg shadow-sm">
                                    @else
                                        <div class="w-16 h-24 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-300"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-gray-900 truncate">{{ $cart->book->title }}</h3>
                                        <p class="text-xs text-gray-500 mb-2">{{ $cart->book->author }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">{{ $cart->quantity }} x @rupiah($cart->book->price)</span>
                                            <span class="font-bold text-gray-900">@rupiah($cart->book->price * $cart->quantity)</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sidebar Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <h2 class="text-lg font-bold text-gray-800 mb-6 pb-4 border-b border-gray-100">Rincian Pembayaran</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex items-center justify-between text-gray-600">
                                <span class="text-sm">Total Harga ({{ $carts->sum('quantity') }} item)</span>
                                <span class="font-medium text-gray-900">@rupiah($subtotal)</span>
                            </div>
                            <div class="flex items-center justify-between text-gray-600">
                                <span class="text-sm">Ongkos Kirim</span>
                                <span class="font-medium text-gray-900">@rupiah($shippingCost)</span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-4 mb-8">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-900 font-bold">Total Tagihan</span>
                                <span class="text-2xl font-black text-primary">@rupiah($total)</span>
                            </div>
                        </div>

                        <button type="submit" id="pay-button" class="w-full bg-primary text-white py-4 rounded-xl font-bold text-lg shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all transform active:scale-95 flex items-center justify-center gap-2 mb-4" {{ $addresses->isEmpty() ? 'disabled' : '' }}>
                            <i class="fas fa-check-circle"></i> Buat Pesanan
                        </button>
                        
                        <div class="flex items-center justify-center gap-4 text-gray-400 opacity-60 grayscale">
                            <i class="fab fa-cc-visa text-2xl"></i>
                            <i class="fab fa-cc-mastercard text-2xl"></i>
                            <i class="fas fa-money-check text-2xl"></i>
                            <span class="text-[10px] font-bold uppercase leading-none">Many more</span>
                        </div>
                        
                        <p class="text-center text-[10px] text-gray-400 mt-6 leading-relaxed">
                            Dengan mengklik tombol di atas, Anda menyetujui <br>
                            <a href="#" class="underline">Syarat & Ketentuan</a> yang berlaku di TukuBuku.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
