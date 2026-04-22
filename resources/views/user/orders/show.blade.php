@extends('layouts.app')

@section('title', 'Detail Pesanan - Toko Buku')

@section('content')
<div class="mb-6">
    <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:underline">&larr; Kembali ke Pesanan</a>
</div>

<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Pesanan #{{ $order->id }}</h1>
            <p class="text-gray-600">Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>
        </div>
        <div class="text-right">
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                @if($order->status == 0) bg-yellow-100 text-yellow-800
                @elseif($order->status == 1) bg-blue-100 text-blue-800
                @elseif($order->status == 2) bg-green-100 text-green-800
                @else bg-red-100 text-red-800 @endif">
                @if($order->status == 0) Pending
                @elseif($order->status == 1) Processing
                @elseif($order->status == 2) Completed
                @else Cancelled @endif
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h2 class="font-semibold mb-2">Informasi Pembeli</h2>
            <p class="text-gray-600">{{ $order->user->name }}</p>
            <p class="text-gray-600">{{ $order->user->email }}</p>
        </div>
        <div>
            <h2 class="font-semibold mb-2">Total Pembayaran</h2>
            <p class="text-2xl font-bold text-indigo-600">@rupiah($order->total_amount)</p>
        </div>
    </div>
</div>

<!-- Order Items -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <h2 class="text-lg font-semibold p-6 border-b">Daftar Buku</h2>
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
                    <td class="px-6 py-4">@rupiah($item->price_at_purchase)</td>
                    <td class="px-6 py-4">{{ $item->quantity }}</td>
                    <td class="px-6 py-4 font-semibold">@rupiah($item->price_at_purchase * $item->quantity)</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($order->status == 0)
    <div class="mt-6">
        <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 font-semibold"
                onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                Batalkan Pesanan
            </button>
        </form>
    </div>
@endif
@endsection