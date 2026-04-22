@extends('layouts.app')

@section('title', 'Kelola Pesanan - Admin Toko Buku')

@section('content')
<h1 class="text-2xl font-bold mb-6">Kelola Pesanan</h1>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left">ID</th>
                <th class="px-6 py-3 text-left">User</th>
                <th class="px-6 py-3 text-left">Total</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Tanggal</th>
                <th class="px-6 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr class="border-t">
                    <td class="px-6 py-4">#{{ $order->id }}</td>
                    <td class="px-6 py-4">{{ $order->user->name }}</td>
                    <td class="px-6 py-4">@rupiah($order->total_amount)</td>
                    <td class="px-6 py-4">
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
                    <td class="px-6 py-4">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:underline">Lihat</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada pesanan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $orders->links() }}
</div>
@endsection