@extends('layouts.app')

@section('title', $book->title . ' - TukuBuku')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumbs -->
    <nav class="flex mb-8 text-sm" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-gray-500">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors flex items-center">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-300 text-xs mx-2"></i>
                    <a href="{{ route('books.index') }}" class="hover:text-primary transition-colors">Buku</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-300 text-xs mx-2"></i>
                    <span class="text-gray-400 truncate max-w-[200px]">{{ $book->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex flex-col lg:flex-row">
            <!-- Book Image Column -->
            <div class="lg:w-2/5 p-6 md:p-10 bg-gray-50/50 flex flex-col items-center justify-start border-b lg:border-b-0 lg:border-r border-gray-100">
                <div class="sticky top-24 w-full flex flex-col items-center">
                    <div class="relative group w-full max-w-sm">
                        <div class="absolute inset-0 bg-primary/20 blur-3xl rounded-full scale-75 group-hover:scale-100 transition-transform duration-700 -z-0 opacity-50"></div>
                        @if($book->cover_image)
                            <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" 
                                 class="relative z-10 w-full rounded-2xl shadow-2xl border border-white transform group-hover:rotate-1 transition-transform duration-500">
                        @else
                            <div class="relative z-10 w-full aspect-[2/3] bg-gray-200 flex flex-col items-center justify-center rounded-2xl shadow-xl border border-white">
                                <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
                                <span class="text-gray-400 font-medium text-lg">No Cover Available</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Quick Stats (Mobile hidden / Tablet+) -->
                    <div class="hidden sm:flex mt-10 w-full justify-around bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="text-center">
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mb-1">Rating</p>
                            <div class="flex items-center justify-center gap-1 text-gray-800 font-black">
                                <i class="fas fa-star text-yellow-400"></i> {{ number_format($book->rating, 1) }}
                            </div>
                        </div>
                        <div class="w-[1px] bg-gray-100"></div>
                        <div class="text-center">
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mb-1">Reviews</p>
                            <div class="text-gray-800 font-black">{{ $book->reviews_count }}</div>
                        </div>
                        <div class="w-[1px] bg-gray-100"></div>
                        <div class="text-center">
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mb-1">Stok</p>
                            <div class="text-gray-800 font-black">{{ $book->stock }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Details Column -->
            <div class="lg:w-3/5 p-6 md:p-10 lg:p-16">
                <div class="mb-8">
                    <span class="inline-block py-1.5 px-3.5 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider mb-4 border border-primary/10">
                        {{ $book->category ?? 'General' }}
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight mb-4">{{ $book->title }}</h1>
                    <div class="flex flex-wrap items-center gap-6">
                        <p class="text-lg text-gray-500 font-medium">Oleh <span class="text-gray-900 font-bold">{{ $book->author }}</span></p>
                        <div class="h-1.5 w-1.5 bg-gray-300 rounded-full"></div>
                        <div class="flex items-center gap-1 text-gray-600">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-xs {{ $i <= round($book->rating) ? '' : 'text-gray-200' }}"></i>
                                @endfor
                            </div>
                            <span class="text-sm font-bold ml-1">{{ number_format($book->rating, 1) }} ({{ $book->reviews_count }} ulasan)</span>
                        </div>
                    </div>
                </div>

                <div class="mb-10 pb-8 border-b border-gray-100">
                    <p class="text-gray-400 text-sm font-bold uppercase tracking-widest mb-2">Harga</p>
                    <div class="flex items-end gap-3">
                        <span class="text-4xl md:text-5xl font-black text-primary">@rupiah($book->price)</span>
                    </div>
                </div>

                <div class="mb-12">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-align-left text-primary"></i> Deskripsi Buku
                    </h2>
                    <div class="prose prose-blue text-gray-600 max-w-none leading-relaxed">
                        {{ $book->description ?? 'Buku ini belum memiliki deskripsi lengkap. Hubungi admin untuk informasi lebih lanjut mengenai sinopsis buku ini.' }}
                    </div>
                </div>

                <!-- Purchase Section -->
                <div class="bg-gray-50 rounded-3xl p-6 md:p-8 border border-gray-100">
                    @auth
                        @if($book->stock > 0)
                            <div class="flex flex-col sm:flex-row items-end gap-4 w-full">
                                <form action="{{ route('cart.store') }}" method="POST" class="flex flex-col sm:flex-row items-end gap-4 w-full sm:w-auto flex-1">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    
                                    <div class="w-full sm:w-auto">
                                        <label for="quantity" class="block text-gray-500 font-bold text-xs uppercase tracking-wider mb-2">Pilih Jumlah</label>
                                        <div class="flex items-center bg-white border border-gray-200 rounded-2xl p-1.5 shadow-sm w-full sm:w-32 justify-between h-[58px]">
                                            <button type="button" onclick="decrement()" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-primary transition-colors">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $book->stock }}"
                                                class="w-12 bg-transparent text-center font-black text-gray-800 focus:outline-none border-none ring-0">
                                            <button type="button" onclick="increment()" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-primary transition-colors">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-1 w-full">
                                        <button type="submit" class="w-full h-[58px] bg-primary text-white px-8 rounded-2xl hover:bg-primary/90 font-black text-lg shadow-xl shadow-primary/30 transition-all transform active:scale-95 flex items-center justify-center gap-3">
                                            <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                                        </button>
                                    </div>
                                </form>

                                <form action="{{ route('wishlist.toggle') }}" method="POST" class="w-full sm:w-auto">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    @php
                                        $inWishlist = Auth::user()->wishlists->contains('book_id', $book->id);
                                    @endphp
                                    <button type="submit" class="w-full sm:w-[58px] h-[58px] bg-white border border-gray-200 text-gray-500 rounded-2xl hover:border-red-200 hover:bg-red-50 transition-all shadow-sm flex items-center justify-center gap-2 group" title="{{ $inWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}">
                                        <i class="fas fa-heart text-2xl {{ $inWishlist ? 'text-red-500' : 'text-gray-300 group-hover:text-red-400' }} transition-colors"></i>
                                        <span class="sm:hidden font-bold {{ $inWishlist ? 'text-red-500' : '' }}">Wishlist</span>
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="w-full p-6 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-4 text-red-600">
                                <i class="fas fa-exclamation-circle text-2xl"></i>
                                <div>
                                    <p class="font-bold">Mohon Maaf!</p>
                                    <p class="text-sm">Stok buku ini sedang habis. Silakan cek kembali nanti.</p>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="flex flex-col items-center gap-6 py-4">
                            <p class="text-gray-500 font-medium text-center">Silakan login terlebih dahulu untuk melakukan pembelian.</p>
                            <a href="{{ route('login') }}" class="w-full sm:w-auto bg-gray-900 text-white px-10 py-4 rounded-2xl font-black text-lg hover:bg-gray-800 transition-all shadow-xl shadow-gray-200 transform active:scale-95">
                                <i class="fas fa-sign-in-alt mr-2"></i> Login Sekarang
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Related Section (Optional Placeholder) -->
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-800 mb-8 flex items-center gap-3">
            <span class="w-10 h-1 bg-primary rounded-full"></span>
            Buku Lainnya yang Mungkin Anda Suka
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            <p class="col-span-full text-gray-400 text-sm italic">Menampilkan rekomendasi buku berdasarkan kategori serupa...</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function increment() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.getAttribute('max'));
        if (parseInt(input.value) < max) {
            input.value = parseInt(input.value) + 1;
        }
    }
    function decrement() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>
@endpush
@endsection