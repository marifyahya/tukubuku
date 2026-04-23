@extends('layouts.app')

@section('title', 'Kelola Pesanan - Admin TukuBuku')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Pesanan</h1>
            <p class="text-gray-500 mt-1">Daftar semua pesanan dari pelanggan.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/50 text-gray-500 text-sm">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">ID Pesanan</th>
                        <th class="px-6 py-4 text-left font-semibold">User</th>
                        <th class="px-6 py-4 text-left font-semibold">Total</th>
                        <th class="px-6 py-4 text-left font-semibold">Status</th>
                        <th class="px-6 py-4 text-left font-semibold">Tanggal</th>
                        <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-900">#{{ $order->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 overflow-hidden flex-shrink-0 border border-primary/20">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name) }}&background=0ea5e9&color=fff" alt="">
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $order->user->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">@rupiah($order->total_amount)</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5 border {{ $order->status->badgeClasses() }}">
                                    <i class="{{ $order->status->icon() }}"></i> {{ $order->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $order->created_at->format('d M Y') }}
                                <span class="text-gray-400 text-xs block">{{ $order->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" title="Detail Pesanan">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                        <i class="fas fa-inbox text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="font-medium">Belum ada pesanan masuk.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection