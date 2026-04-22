@extends('layouts.app')

@section('title', 'Pesanan Saya - Toko Buku')

@section('content')
<h1 class="text-2xl font-bold mb-6">Pesanan Saya</h1>

@if($orders->isEmpty())
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <p class="text-gray-600 mb-4">Anda belum memiliki pesanan.</p>
        <a href="{{ route('books.index') }}" class="text-indigo-600 hover:underline">Jelajahi Buku</a>
    </div>
@else
    <div class="space-y-4">
        @foreach($orders as $order)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="font-bold">Pesanan #{{ $order->id }}</p>
                        <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y H:i') }}</p>
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
                        <p class="text-lg font-bold mt-2">@rupiah($order->total_amount))</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600 hover:underline">Lihat Detail</a>
                    @if($order->status == 0)
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                Batalkan Pesanan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endif
@endsection