@extends('layouts.admin')

@section('title', 'Kelola Pesanan - Admin TukuBuku')
@section('page-title', 'Kelola Pesanan')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Kelola Pesanan</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar semua pesanan dari pelanggan.</p>
    </div>
    <a href="{{ route('admin.orders.export', request()->query()) }}" class="bg-emerald-500 text-white px-5 py-2.5 rounded-xl hover:bg-emerald-600 font-bold shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-2 transform active:scale-95 text-sm">
        <i class="fas fa-file-csv"></i> Export CSV
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-5 mb-6">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
        <!-- Search -->
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID pesanan atau nama user..."
                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm">
        </div>

        <!-- Status Filter -->
        <div class="w-full md:w-48">
            <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm font-medium text-gray-700">
                <option value="">Semua Status</option>
                @foreach(\App\Enums\OrderStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ request('status') !== null && request('status') !== '' && (int)request('status') === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Sort -->
        <div class="w-full md:w-48">
            <select name="sort" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm font-medium text-gray-700">
                <option value="created_at" {{ request('sort', 'created_at') === 'created_at' && request('direction', 'desc') === 'desc' ? 'selected' : '' }}>Terbaru</option>
                <option value="created_at" data-dir="asc" {{ request('sort') === 'created_at' && request('direction') === 'asc' ? 'selected' : '' }}>Terlama</option>
                <option value="total_amount" data-dir="desc" {{ request('sort') === 'total_amount' && request('direction') === 'desc' ? 'selected' : '' }}>Total ↓</option>
                <option value="total_amount" data-dir="asc" {{ request('sort') === 'total_amount' && request('direction') === 'asc' ? 'selected' : '' }}>Total ↑</option>
            </select>
            <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-primary text-white px-5 py-2.5 rounded-xl hover:bg-primary/90 font-semibold transition-all text-sm flex items-center gap-2">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'sort', 'direction']))
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 font-semibold transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-times"></i> Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50/50 text-gray-500 text-xs">
                <tr>
                    <th class="px-5 py-3.5 text-left font-semibold">
                        <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-primary transition-colors inline-flex items-center gap-1">
                            ID
                            @if(request('sort') === 'id')
                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                            @else
                                <i class="fas fa-sort text-gray-300"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-5 py-3.5 text-left font-semibold">User</th>
                    <th class="px-5 py-3.5 text-left font-semibold">
                        <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['sort' => 'total_amount', 'direction' => request('sort') === 'total_amount' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-primary transition-colors inline-flex items-center gap-1">
                            Total
                            @if(request('sort') === 'total_amount')
                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                            @else
                                <i class="fas fa-sort text-gray-300"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-5 py-3.5 text-left font-semibold">Status</th>
                    <th class="px-5 py-3.5 text-left font-semibold">
                        <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('sort', 'created_at') === 'created_at' && request('direction', 'desc') === 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-primary transition-colors inline-flex items-center gap-1">
                            Tanggal
                            @if(request('sort', 'created_at') === 'created_at')
                                <i class="fas fa-sort-{{ request('direction', 'desc') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                            @else
                                <i class="fas fa-sort text-gray-300"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-5 py-3.5 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-3.5 font-bold text-gray-900 text-[10px] font-mono">{{ $order->order_number }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 overflow-hidden flex-shrink-0 border border-primary/20">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name) }}&background=0ea5e9&color=fff&size=32" alt="">
                                </div>
                                <span class="font-medium text-gray-800 text-sm">{{ $order->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 font-bold text-gray-800 text-sm">@rupiah($order->total_amount)</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1 border {{ $order->status->badgeClasses() }}">
                                <i class="{{ $order->status->icon() }} text-[8px]"></i> {{ $order->status->label() }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-600">
                            {{ $order->created_at->format('d M Y') }}
                            <span class="text-gray-400 text-xs block">{{ $order->created_at->format('H:i') }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" title="Detail Pesanan">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="fas fa-inbox text-xl text-gray-400"></i>
                                </div>
                                <p class="font-medium text-sm">Tidak ada pesanan ditemukan.</p>
                                @if(request()->hasAny(['search', 'status']))
                                    <a href="{{ route('admin.orders.index') }}" class="text-primary text-sm font-semibold mt-2 hover:underline">Reset filter</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/30">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Handle sort select with direction
    document.addEventListener('DOMContentLoaded', function() {
        const sortSelect = document.querySelector('select[name="sort"]');
        const directionInput = document.querySelector('input[name="direction"]');
        if (sortSelect && directionInput) {
            sortSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                const dir = selected.getAttribute('data-dir');
                if (dir) {
                    directionInput.value = dir;
                } else {
                    directionInput.value = 'desc';
                }
            });
        }
    });
</script>
@endpush
@endsection