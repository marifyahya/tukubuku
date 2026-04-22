@extends('layouts.app')

@section('title', 'Admin Dashboard - Toko Buku')

@section('content')
<h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-gray-600">Total Buku</p>
        <p class="text-3xl font-bold text-indigo-600">{{ $stats['totalBooks'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-gray-600">Total User</p>
        <p class="text-3xl font-bold text-indigo-600">{{ $stats['totalUsers'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-gray-600">Total Pesanan</p>
        <p class="text-3xl font-bold text-indigo-600">{{ $stats['totalOrders'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-gray-600">Total Pendapatan</p>
        <p class="text-3xl font-bold text-green-600">@rupiah($stats['totalRevenue'])</p>
    </div>
</div>

<!-- Pending Orders Alert -->
@if($stats['pendingOrders'] > 0)
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-8">
        <span class="font-bold">{{ $stats['pendingOrders'] }}</span> pesanan pending perlu diproses.
        <a href="{{ route('admin.orders.index') }}" class="underline">Lihat Pesanan</a>
    </div>
@endif

<!-- Recent Orders -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-bold mb-4">Pesanan Terbaru</h2>
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">ID</th>
                <th class="px-4 py-2 text-left">User</th>
                <th class="px-4 py-2 text-left">Total</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Tanggal</th>
                <th class="px-4 py-2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($stats['recentOrders'] as $order)
                <tr class="border-t">
                    <td class="px-4 py-2">#{{ $order->id }}</td>
                    <td class="px-4 py-2">{{ $order->user->name }}</td>
                    <td class="px-4 py-2">@rupiah($order->total_amount)</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded-full text-xs
                            @if($order->status == 0) bg-yellow-100 text-yellow-800
                            @elseif($order->status == 1) bg-blue-100 text-blue-800
                            @elseif($order->status == 2) bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            @if($order->status == 0) Pending
                            @elseif($order->status == 1) Processing
                            @elseif($order->status == 2) Completed
                            @else Cancelled @endif
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:underline">Lihat</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada pesanan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Quick Links -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <a href="{{ route('admin.books.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg">
        <h3 class="font-bold text-lg mb-2">Kelola Buku</h3>
        <p class="text-gray-600">Tambah, edit, hapus buku</p>
    </a>
    <a href="{{ route('admin.users.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg">
        <h3 class="font-bold text-lg mb-2">Kelola User</h3>
        <p class="text-gray-600">Lihat dan kelola user</p>
    </a>
    <a href="{{ route('admin.orders.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg">
        <h3 class="font-bold text-lg mb-2">Kelola Pesanan</h3>
        <p class="text-gray-600">Lihat dan proses pesanan</p>
    </a>
</div>
@endsection