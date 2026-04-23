@extends('layouts.app')

@section('title', 'Keranjang Belanja - TukuBuku')

@section('content')
<div class="bg-gray-50/50 py-8 min-h-[calc(100vh-200px)]">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-xl">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Keranjang Belanja</h1>
                <p class="text-gray-500 text-sm mt-1">Periksa kembali buku yang ingin Anda beli.</p>
            </div>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if($carts->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center max-w-2xl mx-auto">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-cart text-gray-300 text-4xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">Keranjang Anda masih kosong</h2>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Yuk, cari buku favoritmu dan tambahkan ke keranjang sekarang!</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-xl hover:bg-primary/90 font-bold shadow-lg shadow-primary/30 transition-all transform active:scale-95">
                    <i class="fas fa-search"></i> Mulai Belanja
                </a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cart Items -->
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Header with Select All -->
                        <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-50/80 border-b border-gray-100 text-sm font-semibold text-gray-500">
                            <div class="col-span-6 flex items-center gap-3">
                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" id="select-all" class="w-4.5 h-4.5 rounded border-gray-300 text-primary focus:ring-primary/30 cursor-pointer" checked>
                                    <span>Pilih Semua</span>
                                </label>
                            </div>
                            <div class="col-span-2 text-center">Harga</div>
                            <div class="col-span-2 text-center">Jumlah</div>
                            <div class="col-span-2 text-right">Subtotal</div>
                        </div>

                        <!-- Mobile Select All -->
                        <div class="md:hidden px-6 py-3 bg-gray-50/80 border-b border-gray-100">
                            <label class="flex items-center gap-2 cursor-pointer select-none text-sm font-semibold text-gray-500">
                                <input type="checkbox" id="select-all-mobile" class="w-4.5 h-4.5 rounded border-gray-300 text-primary focus:ring-primary/30 cursor-pointer" checked>
                                <span>Pilih Semua ({{ $carts->count() }} item)</span>
                            </label>
                        </div>

                        <div class="divide-y divide-gray-100">
                            @foreach($carts as $cart)
                                <div class="p-6 flex flex-col md:grid md:grid-cols-12 md:items-center gap-4 hover:bg-gray-50/50 transition-colors cart-item" data-cart-id="{{ $cart->id }}" data-price="{{ $cart->book->price }}" data-quantity="{{ $cart->quantity }}">
                                    <div class="md:col-span-6 flex items-start gap-4">
                                        <!-- Checkbox -->
                                        <div class="flex items-center pt-1 md:pt-3">
                                            <input type="checkbox" name="cart_ids[]" value="{{ $cart->id }}" class="cart-checkbox w-4.5 h-4.5 rounded border-gray-300 text-primary focus:ring-primary/30 cursor-pointer" checked>
                                        </div>

                                        @if($cart->book->cover_image)
                                            <img src="{{ Storage::url($cart->book->cover_image) }}" alt="{{ $cart->book->title }}" class="w-20 h-28 object-cover rounded-xl shadow-sm border border-gray-100">
                                        @else
                                            <div class="w-20 h-28 bg-gray-100 rounded-xl flex items-center justify-center border border-gray-200">
                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h3 class="font-bold text-gray-900 line-clamp-2 mb-1">{{ $cart->book->title }}</h3>
                                            <p class="text-sm text-gray-500 mb-2">{{ $cart->book->author }}</p>
                                            
                                            <!-- Mobile view only: Harga -->
                                            <p class="text-primary font-bold md:hidden">@rupiah($cart->book->price)</p>
                                            
                                            <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" class="mt-2 md:mt-4">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1.5 transition-colors">
                                                    <i class="far fa-trash-alt"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="hidden md:block md:col-span-2 text-center font-bold text-gray-800">
                                        @rupiah($cart->book->price)
                                    </div>
                                    
                                    <div class="md:col-span-2 flex items-center justify-between md:justify-center mt-2 md:mt-0">
                                        <span class="md:hidden text-sm text-gray-500 font-medium">Jumlah:</span>
                                        <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="flex items-center bg-gray-50 border border-gray-200 rounded-lg p-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" max="{{ $cart->book->stock }}"
                                                class="w-12 bg-transparent text-center text-sm font-bold focus:outline-none" onchange="this.form.submit()">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-primary hover:bg-primary hover:text-white rounded-md transition-colors" title="Update">
                                                <i class="fas fa-sync-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <div class="md:col-span-2 flex items-center justify-between md:justify-end mt-2 md:mt-0">
                                        <span class="md:hidden text-sm text-gray-500 font-medium">Subtotal:</span>
                                        <span class="font-black text-gray-900">@rupiah($cart->book->price * $cart->quantity)</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 pb-4 border-b border-gray-100">Ringkasan Belanja</h2>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center justify-between text-gray-600">
                                <span>Item dipilih (<span id="selected-count">{{ $carts->count() }}</span> barang)</span>
                                <span class="font-medium" id="selected-items-total">@rupiah($total)</span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-4 mb-6">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-800 font-bold">Total Pembayaran</span>
                                <span class="text-2xl font-black text-primary" id="checkout-total">@rupiah($total)</span>
                            </div>
                        </div>

                        <form action="{{ route('checkout') }}" method="POST" id="checkout-form">
                            @csrf
                            <div id="checkout-cart-ids"></div>
                            <button type="submit" id="checkout-btn" class="w-full bg-primary text-white py-3.5 rounded-xl font-bold text-lg shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                                <i class="fas fa-lock text-sm opacity-80"></i> Checkout Sekarang
                            </button>
                        </form>
                        
                        <p class="text-center text-xs text-gray-400 mt-4"><i class="fas fa-shield-alt mr-1"></i> Pembayaran aman dan terenkripsi</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.cart-checkbox');
    const selectAll = document.getElementById('select-all');
    const selectAllMobile = document.getElementById('select-all-mobile');
    const selectedCount = document.getElementById('selected-count');
    const selectedItemsTotal = document.getElementById('selected-items-total');
    const checkoutTotal = document.getElementById('checkout-total');
    const checkoutBtn = document.getElementById('checkout-btn');
    const checkoutCartIds = document.getElementById('checkout-cart-ids');

    if (!checkboxes.length) return;

    function formatRupiah(num) {
        return 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updateSummary() {
        let totalItems = 0;
        let totalPrice = 0;

        // Clear hidden inputs
        checkoutCartIds.innerHTML = '';

        checkboxes.forEach(function(cb) {
            if (cb.checked) {
                const item = cb.closest('.cart-item');
                const price = parseFloat(item.dataset.price);
                const qty = parseInt(item.dataset.quantity);
                totalItems += qty;
                totalPrice += price * qty;

                // Add hidden input for this cart id
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'cart_ids[]';
                input.value = cb.value;
                checkoutCartIds.appendChild(input);
            }
        });

        selectedCount.textContent = totalItems;
        selectedItemsTotal.textContent = formatRupiah(totalPrice);
        checkoutTotal.textContent = formatRupiah(totalPrice);

        // Disable checkout if nothing selected
        if (totalItems === 0) {
            checkoutBtn.disabled = true;
            checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
            checkoutBtn.classList.remove('hover:bg-primary/90', 'active:scale-95');
        } else {
            checkoutBtn.disabled = false;
            checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            checkoutBtn.classList.add('hover:bg-primary/90', 'active:scale-95');
        }

        // Update select all checkboxes
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        const someChecked = Array.from(checkboxes).some(cb => cb.checked);
        if (selectAll) {
            selectAll.checked = allChecked;
            selectAll.indeterminate = someChecked && !allChecked;
        }
        if (selectAllMobile) {
            selectAllMobile.checked = allChecked;
            selectAllMobile.indeterminate = someChecked && !allChecked;
        }
    }

    // Individual checkbox change
    checkboxes.forEach(function(cb) {
        cb.addEventListener('change', updateSummary);
    });

    // Select all (desktop)
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(function(cb) {
                cb.checked = selectAll.checked;
            });
            if (selectAllMobile) selectAllMobile.checked = selectAll.checked;
            updateSummary();
        });
    }

    // Select all (mobile)
    if (selectAllMobile) {
        selectAllMobile.addEventListener('change', function() {
            checkboxes.forEach(function(cb) {
                cb.checked = selectAllMobile.checked;
            });
            if (selectAll) selectAll.checked = selectAllMobile.checked;
            updateSummary();
        });
    }

    // Dim unchecked items
    checkboxes.forEach(function(cb) {
        cb.addEventListener('change', function() {
            const item = cb.closest('.cart-item');
            if (cb.checked) {
                item.classList.remove('opacity-50');
            } else {
                item.classList.add('opacity-50');
            }
        });
    });

    // Initial state
    updateSummary();
});
</script>
@endpush
@endsection