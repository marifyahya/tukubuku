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
                    <span class="font-mono bg-white border border-gray-200 px-3 py-1 rounded-lg text-gray-700 font-semibold shadow-sm">{{ $order->order_number }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main: Items & Billing (Now on the Left) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Shipping Address Snapshot -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-primary"></i> Alamat Pengiriman
                        </h3>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <pre class="text-xs text-gray-600 font-sans whitespace-pre-wrap leading-relaxed">{{ $order->shipping_address }}</pre>
                        </div>
                    </div>

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

                    <!-- Payment History -->
                    @if($order->paymentHistories->count() > 1)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-history text-primary"></i> Riwayat Percobaan Pembayaran
                            </h3>
                            <div class="space-y-3">
                                @foreach($order->paymentHistories as $history)
                                    <div class="flex justify-between items-center text-xs p-3 bg-gray-50 rounded-lg border border-gray-100">
                                        <div>
                                            <p class="font-bold text-gray-800 uppercase">{{ str_replace('_', ' ', $history->payment_method ?? 'Belum Pilih Metode') }}</p>
                                            <p class="text-gray-500">{{ $history->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border 
                                            @if($history->payment_status == 'settlement' || $history->payment_status == 'paid') bg-emerald-50 text-emerald-600 border-emerald-100
                                            @elseif($history->payment_status == 'pending') bg-amber-50 text-amber-600 border-amber-100
                                            @else bg-rose-50 text-rose-600 border-rose-100 @endif">
                                            {{ $history->payment_status }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar: Status & Payment (Now on the Right) -->
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
                                    $latestPayment = $order->latestPayment;
                                    $paymentStatus = $latestPayment->payment_status ?? 'pending';
                                    $payStatusClass = match($paymentStatus) {
                                        'settlement', 'paid' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'expire', 'failed', 'cancel', 'deny' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        default => 'bg-gray-50 text-gray-600 border-gray-100',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $payStatusClass }}">
                                    {{ $paymentStatus }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Tanggal Pesanan</span>
                                <span class="text-gray-900 font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            @if($latestPayment && $latestPayment->payment_method)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Metode Pembayaran</span>
                                <span class="text-gray-900 font-medium uppercase">{{ str_replace('_', ' ', $latestPayment->payment_method) }}</span>
                            </div>
                            @endif
                        </div>

                        @if($order->status == \App\Enums\OrderStatus::UNPAID && ($paymentStatus == 'pending'))
                            <div class="mt-8 relative z-10">
                                @php $orderExpiry = $order->created_at->addMinutes(30); @endphp
                                @if($orderExpiry->isFuture())
                                    <div class="mb-4 bg-amber-50 border border-amber-100 rounded-xl p-4 text-center">
                                        <p class="text-[10px] uppercase tracking-wider font-bold text-amber-600 mb-1">Sisa Waktu Pesanan</p>
                                        <div id="payment-timer" class="text-xl font-black text-amber-700 font-mono tracking-tighter" data-expiry="{{ $orderExpiry->toIso8601String() }}">
                                            00:00:00
                                        </div>
                                    </div>
                                @endif

                                <button id="pay-button" class="w-full bg-primary text-white py-4 rounded-xl font-bold text-lg shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                                    <i class="fas fa-wallet"></i> Bayar Sekarang
                                </button>
                                
                                @if($latestPayment && $latestPayment->payment_method)
                                    <button id="change-method-button" class="w-full mt-3 text-primary text-sm font-bold hover:underline transition-all flex items-center justify-center gap-2">
                                        <i class="fas fa-exchange-alt"></i> Ubah Metode Pembayaran
                                    </button>
                                @endif

                                <p class="text-[10px] text-gray-400 text-center mt-3">Selesaikan pembayaran sebelum waktu habis.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Payment Instructions -->
                    @if($order->status == \App\Enums\OrderStatus::UNPAID && $latestPayment && $latestPayment->payment_status == 'pending' && !empty($latestPayment->payment_instructions))
                        @php $instructions = $latestPayment->payment_instructions; @endphp
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-bl-[100px] -z-0"></div>
                            <h3 class="text-sm font-bold text-gray-800 mb-6 flex items-center gap-2 relative z-10">
                                <i class="fas fa-wallet text-amber-500"></i> Instruksi Pembayaran
                            </h3>
                            
                            <div class="bg-amber-50 rounded-xl p-4 border border-amber-100 relative z-10">
                                @if($instructions['type'] == 'va')
                                    <p class="text-[10px] text-amber-600 uppercase font-bold tracking-wider mb-2">Virtual Account {{ $instructions['bank'] }}</p>
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-lg font-black text-amber-900 font-mono tracking-tight truncate" id="va-number">{{ $instructions['va_number'] }}</span>
                                        <button onclick="copyToClipboard('{{ $instructions['va_number'] }}', this)" class="shrink-0 min-w-[65px] text-[10px] bg-white border border-amber-200 text-amber-700 px-2 py-1.5 rounded-lg font-bold hover:bg-amber-100 transition-all">
                                            SALIN
                                        </button>
                                    </div>
                                @elseif($instructions['type'] == 'bill')
                                    <p class="text-[10px] text-amber-600 uppercase font-bold tracking-wider mb-2">Mandiri Bill Payment</p>
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-center gap-2">
                                            <span class="text-xs text-amber-700 font-medium">Biller Code</span>
                                            <span class="font-bold text-amber-900 font-mono">{{ $instructions['biller_code'] }}</span>
                                        </div>
                                        <div class="flex justify-between items-center gap-2">
                                            <span class="text-xs text-amber-700 font-medium">Bill Key</span>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-amber-900 font-mono">{{ $instructions['bill_key'] }}</span>
                                                <button onclick="copyToClipboard('{{ $instructions['bill_key'] }}', this)" class="shrink-0 min-w-[65px] text-[10px] bg-white border border-amber-200 text-amber-700 px-2 py-1 rounded-lg font-bold hover:bg-amber-100 transition-all">SALIN</button>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($instructions['type'] == 'retail')
                                    <p class="text-[10px] text-amber-600 uppercase font-bold tracking-wider mb-2">Gerai {{ $instructions['bank'] }}</p>
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-lg font-black text-amber-900 font-mono tracking-tight truncate">{{ $instructions['payment_code'] }}</span>
                                        <button onclick="copyToClipboard('{{ $instructions['payment_code'] }}', this)" class="shrink-0 min-w-[65px] text-[10px] bg-white border border-amber-200 text-amber-700 px-2 py-1.5 rounded-lg font-bold hover:bg-amber-100 transition-all">SALIN</button>
                                    </div>
                                @endif

                                <div class="pt-3 border-t border-amber-100 flex justify-between items-center">
                                    <span class="text-xs text-amber-700 font-medium">Total Tagihan</span>
                                    <span class="font-black text-amber-900">@rupiah($latestPayment->gross_amount)</span>
                                </div>
                            </div>
                        </div>
                    @endif


                    @if($order->status == \App\Enums\OrderStatus::UNPAID)
                        <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-rose-500 text-sm font-bold hover:underline py-2" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                <i class="fas fa-times-circle mr-1"></i> Batalkan Pesanan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($order->status == \App\Enums\OrderStatus::UNPAID && ($paymentStatus == 'pending'))
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        // Timer Logic
        const timerElement = document.getElementById('payment-timer');
        if (timerElement) {
            const expiryDate = new Date(timerElement.dataset.expiry).getTime();

            function updateTimer() {
                const now = new Date().getTime();
                const distance = expiryDate - now;

                if (distance < 0) {
                    timerElement.innerHTML = "EXPIRED";
                    timerElement.classList.remove('text-amber-700');
                    timerElement.classList.add('text-rose-600');
                    document.getElementById('pay-button').disabled = true;
                    if(document.getElementById('change-method-button')) {
                        document.getElementById('change-method-button').style.display = 'none';
                    }
                    clearInterval(timerInterval);
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timerElement.innerHTML = 
                    (hours < 10 ? "0" + hours : hours) + ":" + 
                    (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                    (seconds < 10 ? "0" + seconds : seconds);
            }

            const timerInterval = setInterval(updateTimer, 1000);
            updateTimer();
        }

        // Payment Logic
        document.addEventListener('DOMContentLoaded', function() {
            // Real-time Payment Sync
            if (typeof Echo !== 'undefined') {
                Echo.private('orders.{{ $order->id }}')
                    .listen('.payment.updated', (e) => {
                        console.log('Payment status updated:', e);
                        // Refresh the page to show new status
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    });
            }
        });

        function initiatePayment(changeMethod = false) {
            const payBtn = document.getElementById('pay-button');
            const originalHtml = payBtn.innerHTML;
            
            payBtn.disabled = true;
            payBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            
            fetch("{{ route('payment.snap-token') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    order_id: "{{ $order->id }}",
                    change_method: changeMethod
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
                payBtn.disabled = false;
                payBtn.innerHTML = originalHtml;
            });
        }

        document.getElementById('pay-button').onclick = function() {
            initiatePayment(false);
        };

        const changeBtn = document.getElementById('change-method-button');
        if (changeBtn) {
            changeBtn.onclick = function() {
                if (confirm('Apakah Anda ingin mengubah metode pembayaran?')) {
                    initiatePayment(true);
                }
            };
        }

        function copyToClipboard(text, btn) {
            const originalText = btn.innerHTML;
            navigator.clipboard.writeText(text).then(() => {
                btn.innerHTML = 'COPIED!';
                btn.classList.replace('text-amber-700', 'text-emerald-700');
                btn.classList.replace('border-amber-200', 'border-emerald-200');
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.replace('text-emerald-700', 'text-amber-700');
                    btn.classList.replace('border-emerald-200', 'border-amber-200');
                }, 2000);
            });
        }
    </script>
@endif
@endsection