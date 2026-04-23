@extends('layouts.app')

@use App\Enums\OrderStatus

@section('title', 'Detail Pesanan - Admin TukuBuku')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary transition-colors font-medium">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Order Info -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-[100px] -z-0"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between mb-8 pb-6 border-b border-gray-100">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-1">Pesanan #{{ $order->id }}</h2>
                    <p class="text-gray-500 text-sm"><i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('d M Y - H:i') }}</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="px-4 py-2 rounded-xl text-sm font-bold inline-flex items-center gap-2 border {{ $order->status->badgeClasses() }}">
                        <i class="{{ $order->status->icon() }}"></i> {{ $order->status->label() }}
                    </span>
                </div>
            </div>

            <div class="mb-2">
                <p class="text-gray-500 text-sm mb-1">Total Pembayaran</p>
                <p class="text-3xl font-black text-primary">@rupiah($order->total_amount)</p>
            </div>
        </div>

        <!-- User Info & Actions -->
        <div class="flex flex-col gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-primary"></i> Informasi Pembeli
                </h2>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-primary/10 overflow-hidden border border-primary/20">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name) }}&background=0ea5e9&color=fff" alt="">
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">{{ $order->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                    </div>
                </div>
            </div>

            @if($order->status !== OrderStatus::COMPLETED && $order->status !== OrderStatus::CANCELLED)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-sync-alt text-primary"></i> Update Status
                </h2>
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <select name="status" id="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium text-gray-700">
                            @foreach(OrderStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ $order->status === $status ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-xl hover:bg-primary/90 font-bold shadow-md shadow-primary/20 transition-all transform active:scale-95">
                        Simpan Status
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-box-open text-primary"></i> Daftar Produk Pesanan
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/30 text-gray-500 text-sm">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Buku</th>
                        <th class="px-6 py-4 text-left font-semibold">Harga Satuan</th>
                        <th class="px-6 py-4 text-center font-semibold">Jumlah</th>
                        <th class="px-6 py-4 text-right font-semibold">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->orderItems as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    @if($item->book->cover_image)
                                        <img src="{{ Storage::url($item->book->cover_image) }}" alt="{{ $item->book->title }}" class="w-12 h-16 object-cover rounded-lg shadow-sm border border-gray-100">
                                    @else
                                        <div class="w-12 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 border border-gray-200">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-gray-800 line-clamp-1">{{ $item->book->title }}</p>
                                        <p class="text-sm text-gray-500">{{ $item->book->author }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-700">@rupiah($item->price_at_purchase)</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 bg-gray-100 rounded-lg text-sm font-bold text-gray-800">{{ $item->quantity }}x</span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">@rupiah($item->price_at_purchase * $item->quantity)</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50/50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-semibold text-gray-600">Total Pembayaran:</td>
                        <td class="px-6 py-4 text-right font-black text-primary text-lg">@rupiah($order->total_amount)</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="flex justify-end">
        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="flex items-center gap-2 bg-white border border-red-200 text-red-600 px-6 py-2.5 rounded-xl hover:bg-red-50 font-bold transition-colors"
                onclick="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus pesanan ini secara permanen?')">
                <i class="fas fa-trash-alt"></i> Hapus Pesanan Permanen
            </button>
        </form>
    </div>
</div>
@endsection