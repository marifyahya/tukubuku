@extends('layouts.admin')

@section('title', 'Admin Dashboard - TukuBuku')
@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-primary to-cyan-600 rounded-2xl p-6 md:p-8 mb-8 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
    <div class="absolute bottom-0 left-1/2 w-48 h-48 bg-white/5 rounded-full translate-y-1/2"></div>
    <div class="relative z-10">
        <h1 class="text-2xl md:text-3xl font-bold mb-2">Selamat datang, {{ Auth::user()->name }}! 👋</h1>
        <p class="text-white/80 text-sm md:text-base">Berikut ringkasan performa toko hari ini.</p>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
    <!-- Total Pendapatan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center text-lg">
                <i class="fas fa-wallet"></i>
            </div>
            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Total</span>
        </div>
        <p class="text-xs text-gray-500 font-medium mb-1">Total Pendapatan</p>
        <p class="text-xl font-bold text-gray-800">@rupiah($stats['totalRevenue'])</p>
    </div>

    <!-- Pendapatan Hari Ini -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-lg">
                <i class="fas fa-chart-line"></i>
            </div>
            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Hari ini</span>
        </div>
        <p class="text-xs text-gray-500 font-medium mb-1">Pendapatan Hari Ini</p>
        <p class="text-xl font-bold text-gray-800">@rupiah($stats['todayRevenue'])</p>
    </div>

    <!-- Total Pesanan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-lg">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">{{ $stats['todayOrders'] }} hari ini</span>
        </div>
        <p class="text-xs text-gray-500 font-medium mb-1">Total Pesanan</p>
        <p class="text-xl font-bold text-gray-800">{{ number_format($stats['totalOrders']) }}</p>
    </div>

    <!-- Total Users & Books -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 bg-indigo-50 text-indigo-500 rounded-xl flex items-center justify-center text-lg">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <p class="text-xs text-gray-500 font-medium mb-1">User / Buku</p>
        <p class="text-xl font-bold text-gray-800">{{ number_format($stats['totalUsers']) }} <span class="text-gray-400 text-sm font-normal">/</span> {{ number_format($stats['totalBooks']) }}</p>
    </div>
</div>

<!-- Action Alerts -->
@if($stats['pendingOrders'] > 0 || $stats['packingOrders'] > 0)
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    @if($stats['pendingOrders'] > 0)
    <div class="bg-amber-50 border border-amber-200 text-amber-800 px-5 py-4 rounded-xl flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-clock text-amber-500"></i>
            </div>
            <div>
                <p class="font-bold text-sm">{{ $stats['pendingOrders'] }} Belum Dibayar</p>
                <p class="text-xs text-amber-600">Menunggu konfirmasi pembayaran</p>
            </div>
        </div>
        <a href="{{ route('admin.orders.index', ['status' => 0]) }}" class="bg-amber-500 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-amber-600 transition-colors whitespace-nowrap">
            Lihat
        </a>
    </div>
    @endif

    @if($stats['packingOrders'] > 0)
    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-5 py-4 rounded-xl flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-box text-blue-500"></i>
            </div>
            <div>
                <p class="font-bold text-sm">{{ $stats['packingOrders'] }} Sedang Dikemas</p>
                <p class="text-xs text-blue-600">Perlu segera dikirim</p>
            </div>
        </div>
        <a href="{{ route('admin.orders.index', ['status' => 1]) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-blue-600 transition-colors whitespace-nowrap">
            Lihat
        </a>
    </div>
    @endif
</div>
@endif

<!-- Order Status Summary + Recent Orders -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Status Breakdown -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-bold text-gray-800 mb-5 flex items-center gap-2">
            <i class="fas fa-chart-pie text-primary"></i> Status Pesanan
        </h2>

        @php
            $statusData = [
                ['label' => 'Belum Dibayar', 'count' => $stats['pendingOrders'], 'color' => 'bg-gray-400', 'text' => 'text-gray-600'],
                ['label' => 'Dikemas', 'count' => $stats['packingOrders'], 'color' => 'bg-blue-400', 'text' => 'text-blue-600'],
                ['label' => 'Dikirim', 'count' => $stats['shippedOrders'], 'color' => 'bg-indigo-400', 'text' => 'text-indigo-600'],
                ['label' => 'Selesai', 'count' => $stats['completedOrders'], 'color' => 'bg-emerald-400', 'text' => 'text-emerald-600'],
                ['label' => 'Dibatalkan', 'count' => $stats['cancelledOrders'], 'color' => 'bg-rose-400', 'text' => 'text-rose-600'],
                ['label' => 'Dikembalikan', 'count' => $stats['returnedOrders'], 'color' => 'bg-amber-400', 'text' => 'text-amber-600'],
            ];
            $maxCount = max(array_column($statusData, 'count')) ?: 1;
        @endphp

        <div class="space-y-3">
            @foreach($statusData as $status)
            <div>
                <div class="flex items-center justify-between text-sm mb-1.5">
                    <span class="font-medium text-gray-600">{{ $status['label'] }}</span>
                    <span class="font-bold {{ $status['text'] }}">{{ $status['count'] }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="{{ $status['color'] }} rounded-full h-2 transition-all duration-500" style="width: {{ $maxCount > 0 ? ($status['count'] / $maxCount * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-5 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.orders.index') }}" class="text-primary text-sm font-semibold hover:underline flex items-center gap-1">
                Lihat Semua Pesanan <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-clock text-primary"></i> Pesanan Terbaru
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="text-primary text-sm font-semibold hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/50 text-gray-500 text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">ID</th>
                        <th class="px-5 py-3 text-left font-semibold">User</th>
                        <th class="px-5 py-3 text-left font-semibold">Total</th>
                        <th class="px-5 py-3 text-left font-semibold">Status</th>
                        <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($stats['recentOrders'] as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3 text-sm font-bold text-gray-900 font-mono">{{ $order->order_number }}</td>
                            <td class="px-5 py-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-primary/10 overflow-hidden flex-shrink-0 border border-primary/20">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name) }}&background=0ea5e9&color=fff&size=28" alt="">
                                    </div>
                                    <span class="font-medium text-gray-700 text-xs">{{ $order->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-sm font-bold text-gray-800">@rupiah($order->total_amount)</td>
                            <td class="px-5 py-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1 border {{ $order->status->badgeClasses() }}">
                                    <i class="{{ $order->status->icon() }} text-[8px]"></i> {{ $order->status->label() }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-primary hover:text-white transition-all" title="Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                                        <i class="fas fa-inbox text-xl text-gray-300"></i>
                                    </div>
                                    <p class="text-sm font-medium">Belum ada pesanan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Revenue Summary -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-base font-bold text-gray-800 mb-5 flex items-center gap-2">
        <i class="fas fa-coins text-primary"></i> Ringkasan Pendapatan
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center p-4 bg-gray-50 rounded-xl">
            <p class="text-xs text-gray-500 font-medium mb-2">Hari Ini</p>
            <p class="text-2xl font-black text-gray-800">@rupiah($stats['todayRevenue'])</p>
        </div>
        <div class="text-center p-4 bg-primary/5 rounded-xl border border-primary/10">
            <p class="text-xs text-primary font-medium mb-2">7 Hari Terakhir</p>
            <p class="text-2xl font-black text-primary">@rupiah($stats['weekRevenue'])</p>
        </div>
        <div class="text-center p-4 bg-gray-50 rounded-xl">
            <p class="text-xs text-gray-500 font-medium mb-2">Sepanjang Waktu</p>
            <p class="text-2xl font-black text-gray-800">@rupiah($stats['totalRevenue'])</p>
        </div>
    </div>
</div>
@endsection