@extends('layouts.app')

@section('title', 'Detail Pesanan - TukuBuku')

@section('content')
<div class="bg-gray-50/50 py-8 min-h-[calc(100vh-200px)]">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary transition-colors font-medium">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 md:p-8 border-b border-gray-100 relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-[100px] -z-0"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan</h1>
                            <div class="flex items-center gap-3 text-sm text-gray-500 mt-2">
                                <span class="font-mono bg-gray-100 px-2 py-1 rounded text-gray-700 font-semibold">INV-{{ $order->id }}-{{ $order->created_at->format('Ymd') }}</span>
                                <span><i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="px-4 py-2 rounded-xl text-sm font-bold inline-flex items-center gap-2 shadow-sm
                                @if($order->status == 0) bg-orange-100 text-orange-700
                                @elseif($order->status == 1) bg-blue-100 text-blue-700
                                @elseif($order->status == 2) bg-green-100 text-green-700
                                @else bg-red-100 text-red-700 @endif">
                                @if($order->status == 0) <i class="fas fa-clock"></i> Menunggu Pembayaran
                                @elseif($order->status == 1) <i class="fas fa-spinner fa-spin"></i> Sedang Diproses
                                @elseif($order->status == 2) <i class="fas fa-check-circle"></i> Selesai
                                @else <i class="fas fa-times-circle"></i> Dibatalkan @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Informasi Pembeli</h2>
                        <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div class="w-12 h-12 rounded-full bg-primary/10 overflow-hidden border border-primary/20">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name) }}&background=0ea5e9&color=fff" alt="">
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $order->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Ringkasan Pembayaran</h2>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 h-[82px] flex items-center justify-between">
                            <span class="text-gray-600 font-medium">Total Harga</span>
                            <span class="text-2xl font-black text-primary">@rupiah($order->total_amount)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <h2 class="text-lg font-bold p-6 border-b border-gray-100 flex items-center gap-2">
                    <i class="fas fa-box-open text-primary"></i> Daftar Produk
                </h2>
                
                <div class="divide-y divide-gray-100">
                    @foreach($order->orderItems as $item)
                        <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 hover:bg-gray-50/50 transition-colors">
                            @if($item->book->cover_image)
                                <img src="{{ Storage::url($item->book->cover_image) }}" alt="{{ $item->book->title }}" class="w-16 h-24 object-cover rounded-lg shadow-sm border border-gray-100">
                            @else
                                <div class="w-16 h-24 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 text-lg line-clamp-1">{{ $item->book->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $item->book->author }}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-sm font-semibold text-gray-600">@rupiah($item->price_at_purchase)</span>
                                    <span class="text-xs text-gray-400">x</span>
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-xs font-bold rounded">{{ $item->quantity }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-4 sm:mt-0 text-right w-full sm:w-auto">
                                <p class="text-sm text-gray-500 mb-1">Subtotal</p>
                                <p class="font-black text-gray-900 text-lg">@rupiah($item->price_at_purchase * $item->quantity)</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="p-6 bg-gray-50/50 border-t border-gray-100 flex justify-between items-center">
                    <span class="font-bold text-gray-600">Total Pembayaran</span>
                    <span class="text-xl font-black text-primary">@rupiah($order->total_amount)</span>
                </div>
            </div>

            @if($order->status == 0)
                <div class="flex justify-end mt-8">
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-white border border-red-200 text-red-600 px-8 py-3 rounded-xl hover:bg-red-50 font-bold transition-colors flex items-center gap-2"
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                            <i class="fas fa-times-circle"></i> Batalkan Pesanan
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection