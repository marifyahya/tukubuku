@use('App\Enums\OrderStatus')
@extends('layouts.app')

@section('title', 'Pesanan Saya - TukuBuku')

@section('content')
<div class="bg-gray-50 py-8 min-h-[calc(100vh-200px)]">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <div class="w-full md:w-1/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-28">
                    <div class="p-6 text-center border-b border-gray-100">
                        <div class="w-24 h-24 rounded-full bg-gray-200 mx-auto mb-4 overflow-hidden border-4 border-white shadow-lg">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0ea5e9&color=fff&size=150" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <nav class="p-4 flex flex-col gap-1">
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary rounded-xl font-medium transition-colors">
                            <i class="fas fa-user w-5 text-center"></i> Profil Saya
                        </a>
                        <a href="{{ route('addresses.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary rounded-xl font-medium transition-colors">
                            <i class="fas fa-map-marker-alt w-5 text-center"></i> Buku Alamat
                        </a>
                        <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl font-medium transition-colors">
                            <i class="fas fa-shopping-bag w-5 text-center"></i> Pesanan Saya
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="block w-full">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-xl font-medium transition-colors w-full text-left">
                                <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Content -->
            <div class="w-full md:w-3/4 space-y-6">
                <!-- Header Title -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-shopping-bag text-primary"></i> Pesanan Saya
                    </h2>

                    <!-- Tabs -->
                    <div class="flex overflow-x-auto pb-2 mb-6 gap-2 no-scrollbar">
                        <a href="{{ route('orders.index') }}" class="flex-none px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ request('tab') === null ? 'bg-primary text-white shadow-md shadow-primary/30' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Semua</a>
                        
                        @foreach(OrderStatus::cases() as $status)
                        <a href="{{ route('orders.index', ['tab' => $status->value]) }}" class="flex-none px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ request('tab') != '' && request('tab') == $status->value ? 'bg-primary text-white shadow-md shadow-primary/30' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ $status->label() }}
                        </a>
                        @endforeach
                    </div>

                    @if($orders->isEmpty())
                        <div class="text-center py-12 border-2 border-dashed border-gray-100 rounded-2xl">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                <i class="fas fa-box-open text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Belum ada pesanan</h3>
                            <p class="text-gray-500 mb-6 max-w-sm mx-auto">Anda belum memiliki pesanan dengan status ini.</p>
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-xl hover:bg-primary/90 font-bold shadow-lg shadow-primary/30 transition-all transform active:scale-95">
                                Mulai Belanja
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="border border-gray-100 rounded-2xl overflow-hidden hover:border-primary/30 transition-colors group">
                                    <!-- Order Header -->
                                    <div class="bg-gray-50/50 px-5 py-3 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-shopping-bag text-gray-400"></i>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">{{ $order->created_at->format('d M Y') }}</p>
                                                <p class="text-xs text-gray-500 font-mono">{{ $order->order_number }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="px-3 py-1 rounded-lg text-xs font-bold inline-block {{ $order->status->badgeClasses() }}">
                                                {{ $order->status->label() }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Order Body -->
                                    <div class="p-5 flex flex-col md:flex-row justify-between gap-6">
                                        <div class="flex items-start gap-4 flex-1">
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
                                        
                                        <div class="flex flex-col sm:flex-row md:flex-col lg:flex-row items-start sm:items-center md:items-end lg:items-center gap-4 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6 flex-shrink-0">
                                            <div>
                                                <p class="text-sm text-gray-500 mb-0.5">Total Belanja</p>
                                                <p class="text-lg font-black text-gray-900">@rupiah($order->total_amount + $order->shipping_cost)</p>
                                            </div>
                                            <div class="w-full sm:w-auto flex flex-col gap-2">
                                                <a href="{{ route('orders.show', $order->order_number) }}" class="w-full text-center bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl font-bold hover:bg-gray-50 transition-colors">Lihat Detail</a>
                                                
                                                @if($order->status === OrderStatus::UNPAID)
                                                    <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="w-full text-center px-4 py-2 border border-red-200 text-red-600 rounded-xl font-bold hover:bg-red-50 transition-colors" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
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
                            <div class="mt-6">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection