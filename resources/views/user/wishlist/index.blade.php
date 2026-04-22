@extends('layouts.app')

@section('title', 'Wishlist Saya - TukuBuku')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Header Section -->
    <div class="mb-12 text-center max-w-2xl mx-auto">
        <h1 class="text-4xl font-black text-gray-900 mb-4">
            <i class="fas fa-heart text-red-500 mr-2"></i> Wishlist Saya
        </h1>
        <p class="text-gray-500">Koleksi buku-buku yang Anda minati. Segera tambahkan ke keranjang sebelum kehabisan!</p>
    </div>

    @if(session('success'))
        <div class="max-w-3xl mx-auto mb-8 bg-green-50 text-green-600 px-6 py-4 rounded-2xl border border-green-100 flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Books Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @forelse($wishlists as $wishlist)
            @include('partials.book-card', ['book' => $wishlist->book])
        @empty
            <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
                <div class="w-32 h-32 bg-red-50 rounded-full flex items-center justify-center mb-6 border border-red-100">
                    <i class="far fa-heart text-5xl text-red-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Wishlist Masih Kosong</h3>
                <p class="text-gray-500 max-w-sm">Anda belum menambahkan buku apa pun ke dalam daftar keinginan. Yuk, mulai menjelajah!</p>
                <a href="{{ route('books.index') }}" class="mt-8 bg-primary text-white px-8 py-3 rounded-2xl font-bold hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 transform active:scale-95">
                    Jelajahi Koleksi Buku
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
