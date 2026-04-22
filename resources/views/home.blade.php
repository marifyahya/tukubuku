@extends('layouts.app')

@section('title', 'Toko Buku - Toko Buku Online')

@section('content')
<!-- Hero Section -->
<div class="bg-indigo-600 text-white py-16 rounded-xl mb-12">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4">Selamat Datang di Toko Buku</h1>
        <p class="text-xl mb-8">Temukan buku favorit Anda dengan harga terbaik</p>
        <a href="{{ route('books.index') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block">
            Jelajahi Koleksi
        </a>
    </div>
</div>

<!-- Featured Books -->
<h2 class="text-2xl font-bold mb-6">Buku Terbaru</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($featuredBooks as $book)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            @if($book->cover_image)
                <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400">No Cover</span>
                </div>
            @endif
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-1 truncate">{{ $book->title }}</h3>
                <p class="text-gray-600 text-sm mb-2">{{ $book->author }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-indigo-600 font-bold">@rupiah($book->price))</span>
                    <span class="text-sm text-gray-500">Stok: {{ $book->stock }}</span>
                </div>
                <a href="{{ route('books.show', $book->id) }}" class="block mt-4 text-center bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                    Lihat Detail
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-3 text-center py-8 text-gray-500">
            <p>Belum ada buku tersedia.</p>
        </div>
    @endforelse
</div>
@endsection