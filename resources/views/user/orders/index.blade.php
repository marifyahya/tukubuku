@extends('layouts.app')

@section('title', 'Pesanan Saya - TukuBuku')

@section('content')
<div class="bg-gray-50/50 py-8 min-h-[calc(100vh-200px)]">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-xl">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Pesanan Saya</h1>
                <p class="text-gray-500 text-sm mt-1">Lacak dan kelola semua pesanan Anda di sini.</p>
            </div>
        </div>

        @if($orders->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center max-w-2xl mx-auto">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-box-open text-gray-300 text-4xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">Belum ada pesanan</h2>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Anda belum pernah melakukan pemesanan. Yuk, temukan buku menarik di katalog kami!</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-xl hover:bg-primary/90 font-bold shadow-lg shadow-primary/30 transition-all transform active:scale-95">
                    <i class="fas fa-search"></i> Mulai Belanja
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Order Header -->
                        <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <i class="fas fa-shopping-bag text-gray-400 text-xl hidden sm:block"></i>
                                <div>
                                    <p class="font-bold text-gray-900">Belanja • {{ $order->created_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500 font-mono mt-0.5">INV-{{ $order->id }}-{{ $order->created_at->format('Ymd') }}</p>
                                </div>
                            </div>
                            <div>
                                <span class="px-3 py-1.5 rounded-lg text-xs font-bold inline-flex items-center gap-1.5
                                    @if($order->status == 0) bg-orange-100 text-orange-700
                                    @elseif($order->status == 1) bg-blue-100 text-blue-700
                                    @elseif($order->status == 2) bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700 @endif">
                                    @if($order->status == 0) <i class="fas fa-clock"></i> Menunggu Pembayaran
                                    @elseif($order->status == 1) <i class="fas fa-spinner fa-spin"></i> Diproses
                                    @elseif($order->status == 2) <i class="fas fa-check"></i> Selesai
                                    @else <i class="fas fa-times"></i> Dibatalkan @endif
                                </span>
                            </div>
                        </div>

                        <!-- Order Body -->
                        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex items-start gap-4 flex-1">
                                <!-- Show just the first item's image as a thumbnail -->
                                @if($order->orderItems->first() && $order->orderItems->first()->book->cover_image)
                                    <img src="{{ Storage::url($order->orderItems->first()->book->cover_image) }}" alt="Cover" class="w-16 h-24 object-cover rounded-lg shadow-sm border border-gray-100">
                                @else
                                    <div class="w-16 h-24 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                                
                                <div>
                                    <h3 class="font-bold text-gray-800 line-clamp-1">{{ $order->orderItems->first()->book->title ?? 'Buku' }}</h3>
                                    <p class="text-sm text-gray-500 mb-2">{{ $order->orderItems->first()->quantity }} barang x @rupiah($order->orderItems->first()->price_at_purchase)</p>
                                    
                                    @if($order->orderItems->count() > 1)
                                        <p class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded inline-block">
                                            +{{ $order->orderItems->count() - 1 }} produk lainnya
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row md:flex-col lg:flex-row items-start sm:items-center md:items-end lg:items-center gap-6 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6 flex-shrink-0">
                                <div>
                                    <p class="text-sm text-gray-500 mb-0.5">Total Belanja</p>
                                    <p class="text-lg font-black text-gray-900">@rupiah($order->total_amount)</p>
                                </div>
                                <div class="w-full sm:w-auto flex flex-col gap-2">
                                    <a href="{{ route('orders.show', $order->id) }}" class="w-full sm:w-auto text-center bg-primary text-white px-6 py-2 rounded-xl font-bold hover:bg-primary/90 transition-colors shadow-md shadow-primary/20">Lihat Detail</a>
                                    
                                    @if($order->status == 0)
                                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full sm:w-auto text-center px-6 py-2 border border-red-200 text-red-600 rounded-xl font-semibold hover:bg-red-50 transition-colors" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($orders->hasPages())
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection