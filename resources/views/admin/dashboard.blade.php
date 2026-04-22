@extends('layouts.app')

@section('title', 'Admin Dashboard - TukuBuku')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
            <p class="text-gray-500 mt-1">Ringkasan performa toko dan aktivitas terbaru.</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
            <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-2xl mr-4">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Buku</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['totalBooks'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
            <div class="w-14 h-14 bg-indigo-50 text-indigo-500 rounded-xl flex items-center justify-center text-2xl mr-4">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total User</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['totalUsers'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
            <div class="w-14 h-14 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-2xl mr-4">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Pesanan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['totalOrders'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
            <div class="w-14 h-14 bg-green-50 text-green-500 rounded-xl flex items-center justify-center text-2xl mr-4">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-800">@rupiah($stats['totalRevenue'])</p>
            </div>
        </div>
    </div>

    <!-- Pending Orders Alert -->
    @if($stats['pendingOrders'] > 0)
        <div class="bg-orange-50 border border-orange-200 text-orange-800 px-6 py-4 rounded-xl mb-8 flex flex-col sm:flex-row sm:items-center justify-between shadow-sm gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-orange-500 text-lg"></i>
                </div>
                <p><span class="font-bold">{{ $stats['pendingOrders'] }}</span> pesanan pending perlu segera diproses.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="bg-orange-500 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-orange-600 transition-colors whitespace-nowrap text-center">
                Proses Sekarang
            </a>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-clock text-primary"></i> Pesanan Terbaru
                </h2>
                <a href="{{ route('admin.orders.index') }}" class="text-primary text-sm font-bold hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/50 text-gray-500 text-sm">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">ID</th>
                            <th class="px-6 py-4 text-left font-semibold">User</th>
                            <th class="px-6 py-4 text-left font-semibold">Total</th>
                            <th class="px-6 py-4 text-left font-semibold">Status</th>
                            <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($stats['recentOrders'] as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-gray-900">#{{ $order->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary/10 overflow-hidden flex-shrink-0 border border-primary/20">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name) }}&background=0ea5e9&color=fff" alt="">
                                        </div>
                                        <span class="font-medium">{{ $order->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-800">@rupiah($order->total_amount)</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold
                                        @if($order->status == 0) bg-orange-100 text-orange-700 border border-orange-200
                                        @elseif($order->status == 1) bg-blue-100 text-blue-700 border border-blue-200
                                        @elseif($order->status == 2) bg-green-100 text-green-700 border border-green-200
                                        @else bg-red-100 text-red-700 border border-red-200 @endif">
                                        @if($order->status == 0) Pending
                                        @elseif($order->status == 1) Processing
                                        @elseif($order->status == 2) Completed
                                        @else Cancelled @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-primary hover:text-white transition-all">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                            <i class="fas fa-inbox text-2xl text-gray-400"></i>
                                        </div>
                                        <p class="font-medium">Belum ada pesanan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="space-y-4">
            <h2 class="text-lg font-bold text-gray-800 mb-4 px-2">Akses Cepat</h2>
            
            <a href="{{ route('admin.books.index') }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:border-primary hover:shadow-md transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-xl group-hover:bg-primary group-hover:text-white transition-colors">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 group-hover:text-primary transition-colors">Kelola Buku</h3>
                        <p class="text-gray-500 text-sm mt-0.5">Tambah, edit, hapus buku</p>
                    </div>
                    <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-primary transition-colors"></i>
                </div>
            </a>
            
            <a href="{{ route('admin.users.index') }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:border-primary hover:shadow-md transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-xl flex items-center justify-center text-xl group-hover:bg-primary group-hover:text-white transition-colors">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 group-hover:text-primary transition-colors">Kelola User</h3>
                        <p class="text-gray-500 text-sm mt-0.5">Lihat dan kelola user</p>
                    </div>
                    <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-primary transition-colors"></i>
                </div>
            </a>
            
            <a href="{{ route('admin.orders.index') }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:border-primary hover:shadow-md transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-xl group-hover:bg-primary group-hover:text-white transition-colors">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 group-hover:text-primary transition-colors">Kelola Pesanan</h3>
                        <p class="text-gray-500 text-sm mt-0.5">Lihat dan proses pesanan</p>
                    </div>
                    <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-primary transition-colors"></i>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection