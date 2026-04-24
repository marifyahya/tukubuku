@extends('layouts.app')

@section('title', 'Detail Pesanan - TukuBuku')

@section('content')
<div class="bg-gray-50/50 py-8 min-h-[calc(100vh-200px)]">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary transition-colors font-medium">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
                </a>
                <div class="flex items-center gap-3 text-sm text-gray-500">
                    <span class="font-mono bg-white border border-gray-200 px-3 py-1 rounded-lg text-gray-700 font-semibold shadow-sm">INV-{{ $order->id }}-{{ $order->created_at->format('Ymd') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sidebar: Status & Payment -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-bl-[100px] -z-0"></div>
                        <h3 class="text-sm font-bold text-gray-800 mb-6 flex items-center gap-2 relative z-10">
                            <i class="fas fa-info-circle text-primary"></i> Status Pesanan
                        </h3>
                        
                        <div class="space-y-4 relative z-10">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Status Pesanan</span>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1 border {{ $order->status->badgeClasses() }}">
                                    <i class="{{ $order->status->icon() }} text-[8px]"></i> {{ $order->status->label() }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Status Pembayaran</span>
                                @php
                                    $payStatusClass = match($order->payment_status) {
                                        'paid' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'failed' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        default => 'bg-gray-50 text-gray-600 border-gray-100',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $payStatusClass }}">
                                    {{ $order->payment_status ?? 'pending' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Tanggal Pesanan</span>
                                <span class="text-gray-900 font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            @if($order->payment_method)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Metode Pembayaran</span>
                                <span class="text-gray-900 font-medium uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                            </div>
                            @endif
                        </div>

                        @if($order->status == \App\Enums\OrderStatus::UNPAID && $order->payment_status == 'pending')
                            <div class="mt-8 relative z-10">
                                <button id="pay-button" class="w-full bg-primary text-white py-4 rounded-xl font-bold text-lg shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                                    <i class="fas fa-wallet"></i> Bayar Sekarang
                                </button>
                                <p class="text-[10px] text-gray-400 text-center mt-3">Selesaikan pembayaran untuk memproses pesanan Anda.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Shipping Address Snapshot -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-primary"></i> Alamat Pengiriman
                        </h3>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <pre class="text-xs text-gray-600 font-sans whitespace-pre-wrap leading-relaxed">{{ $order->shipping_address }}</pre>
                        </div>
                    </div>

                    @if($order->status == \App\Enums\OrderStatus::UNPAID)
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-rose-500 text-sm font-bold hover:underline py-2" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                <i class="fas fa-times-circle mr-1"></i> Batalkan Pesanan
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Main: Items & Billing -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Items -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100">
                            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-shopping-bag text-primary"></i> Daftar Buku
                            </h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($order->orderItems as $item)
                                <div class="p-6 flex items-center gap-4">
                                    @if($item->book->cover_image)
                                        <img src="{{ Storage::url($item->book->cover_image) }}" alt="{{ $item->book->title }}" class="w-16 h-24 object-cover rounded-lg shadow-sm">
                                    @else
                                        <div class="w-16 h-24 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-300"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-gray-900 truncate">{{ $item->book->title }}</h4>
                                        <p class="text-xs text-gray-500">{{ $item->book->author }}</p>
                                        <p class="text-sm text-gray-600 mt-2">{{ $item->quantity }} x @rupiah($item->price_at_purchase)</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">@rupiah($item->price_at_purchase * $item->quantity)</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Billing Detail -->
                        <div class="p-6 bg-gray-50/50 border-t border-gray-100 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Subtotal Produk</span>
                                <span class="font-medium text-gray-900">@rupiah($order->total_amount)</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Ongkos Kirim</span>
                                <span class="font-medium text-gray-900">@rupiah($order->shipping_cost)</span>
                            </div>
                            <div class="flex justify-between pt-3 border-t border-gray-100">
                                <span class="font-bold text-gray-900">Total Pembayaran</span>
                                <span class="text-2xl font-black text-primary">@rupiah($order->total_amount + $order->shipping_cost)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($order->status == \App\Enums\OrderStatus::UNPAID && $order->payment_status == 'pending')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // Show loading state if needed
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            
            fetch("{{ route('payment.snap-token') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    order_id: "{{ $order->id }}"
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: function(result){
                            window.location.reload();
                        },
                        onPending: function(result){
                            window.location.reload();
                        },
                        onError: function(result){
                            alert("Pembayaran gagal!");
                            window.location.reload();
                        },
                        onClose: function(){
                            window.location.reload();
                        }
                    });
                } else {
                    alert("Gagal mendapatkan token pembayaran: " + data.message);
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Terjadi kesalahan sistem.");
                window.location.reload();
            });
        };
    </script>
@endif
@endsection