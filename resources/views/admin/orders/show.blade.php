@extends('layouts.app')

@section('title', 'Detail Pesanan - Admin Toko Buku')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:underline">&larr; Kembali ke Pesanan</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Order Info -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">Informasi Pesanan</h2>
        <p class="mb-2"><span class="font-semibold">ID:</span> #{{ $order->id }}</p>
        <p class="mb-2"><span class="font-semibold">Tanggal:</span> {{ $order->created_at->format('d M Y H:i') }}</p>
        <p class="mb-2">
            <span class="font-semibold">Status:</span>
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
        </p>
        <p class="text-2xl font-bold text-indigo-600 mt-4">Total: @rupiah($order->total_amount))</p>
    </div>

    <!-- User Info -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">Informasi Pembeli</h2>
        <p class="mb-2"><span class="font-semibold">Nama:</span> {{ $order->user->name }}</p>
        <p class="mb-2"><span class="font-semibold">Email:</span> {{ $order->user->email }}</p>
    </div>
</div>

<!-- Update Status -->
@if($order->status != 3)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Update Status</h2>
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="flex gap-4 items-end">
            @csrf
            @method('PUT')
            <div>
                <label for="status" class="block text-gray-700 font-semibold mb-2">Status</label>
                <select name="status" id="status" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>Processing</option>
                    <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Completed</option>
                    <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 font-semibold">
                Update Status
            </button>
        </form>
    </div>
@endif

<!-- Order Items -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <h2 class="text-xl font-bold p-6 border-b">Daftar Buku</h2>
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left">Buku</th>
                <th class="px-6 py-3 text-left">Harga</th>
                <th class="px-6 py-3 text-left">Jumlah</th>
                <th class="px-6 py-3 text-left">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr class="border-t">
                    <td class="px-6 py-4">
                        <p class="font-semibold">{{ $item->book->title }}</p>
                        <p class="text-sm text-gray-600">{{ $item->book->author }}</p>
                    </td>
                    <td class="px-6 py-4">@rupiah($item->price_at_purchase))</td>
                    <td class="px-6 py-4">{{ $item->quantity }}</td>
                    <td class="px-6 py-4 font-semibold">@rupiah($item->price_at_purchase * $item->quantity))</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Delete Order -->
<div class="mt-6">
    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 font-semibold"
            onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
            Hapus Pesanan
        </button>
    </form>
</div>
@endsection