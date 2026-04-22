@extends('layouts.app')

@section('title', 'Jelajahi Koleksi Buku - TukuBuku')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Header Section -->
    <div class="mb-12 text-center max-w-2xl mx-auto">
        <h1 class="text-4xl font-black text-gray-900 mb-4">Jelajahi Koleksi Buku</h1>
        <p class="text-gray-500">Temukan ribuan judul buku pilihan dari berbagai penulis ternama dunia hanya di TukuBuku.</p>
    </div>

    <!-- Search & Filter Bar -->
    <div class="mb-12">
        <form action="{{ route('books.index') }}" method="GET" class="relative max-w-3xl mx-auto">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari judul buku, penulis, atau kategori..." 
                    class="w-full pl-14 pr-32 py-5 bg-white border border-gray-100 rounded-3xl shadow-xl shadow-gray-100/50 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none text-gray-700 font-medium">
                <div class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search text-xl"></i>
                </div>
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 bg-primary text-white px-8 py-3 rounded-2xl font-bold hover:bg-primary/90 transition-all transform active:scale-95 shadow-lg shadow-primary/20">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Results Info -->
    @if(request('search'))
        <div class="mb-8 flex items-center gap-2 text-gray-500 font-medium">
            <i class="fas fa-info-circle text-primary"></i>
            <span>Menampilkan hasil pencarian untuk: <span class="text-gray-900 font-bold">"{{ request('search') }}"</span></span>
            <a href="{{ route('books.index') }}" class="ml-2 text-red-500 hover:underline text-sm font-bold">
                <i class="fas fa-times-circle"></i> Hapus
            </a>
        </div>
    @endif

    <!-- Books Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @forelse($books as $book)
            @include('partials.book-card', ['book' => $book])
        @empty
            <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
                <div class="w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center mb-6 border border-gray-100">
                    <i class="fas fa-book-open text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Buku Tidak Ditemukan</h3>
                <p class="text-gray-500 max-w-sm">Maaf, kami tidak dapat menemukan buku yang Anda cari. Coba gunakan kata kunci lain atau jelajahi kategori populer.</p>
                <a href="{{ route('books.index') }}" class="mt-8 text-primary font-bold hover:underline">
                    Lihat Semua Koleksi
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-16 flex justify-center">
        <div class="pagination-wrapper px-6 py-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
            {{ $books->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection