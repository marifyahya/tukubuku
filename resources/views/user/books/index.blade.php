@extends('layouts.app')

@section('title', 'Daftar Buku - Toko Buku')

@section('content')
<h1 class="text-2xl font-bold mb-6">Daftar Buku</h1>

<!-- Search Form -->
<form action="{{ route('books.index') }}" method="GET" class="mb-6">
    <div class="flex gap-2">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari judul atau penulis..." 
            class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
            Cari
        </button>
    </div>
</form>

<!-- Books Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($books as $book)
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
                    <span class="text-indigo-600 font-bold">@rupiah($book->price)</span>
                    <span class="text-sm text-gray-500">Stok: {{ $book->stock }}</span>
                </div>
                <a href="{{ route('books.show', $book) }}" class="block mt-4 text-center bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                    Lihat Detail
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-4 text-center py-8 text-gray-500">
            <p>Buku tidak ditemukan.</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $books->links() }}
</div>
@endsection