@extends('layouts.app')

@section('title', $book->title . ' - Toko Buku')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Book Cover -->
        <div>
            @if($book->cover_image)
                <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="w-full rounded-lg">
            @else
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg">
                    <span class="text-gray-400">No Cover</span>
                </div>
            @endif
        </div>

        <!-- Book Details -->
        <div class="md:col-span-2">
            <h1 class="text-3xl font-bold mb-2">{{ $book->title }}</h1>
            <p class="text-gray-600 text-lg mb-4">oleh {{ $book->author }}</p>
            
            <div class="mb-4">
                <span class="text-2xl font-bold text-indigo-600">@rupiah($book->price))</span>
            </div>

            <div class="mb-4">
                <span class="text-gray-600">Stok: </span>
                <span class="font-semibold">{{ $book->stock > 0 ? $book->stock : 'Habis' }}</span>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Deskripsi</h2>
                <p class="text-gray-600">{{ $book->description ?? 'Tidak ada deskripsi.' }}</p>
            </div>

            @auth
                @if($book->stock > 0)
                    <form action="{{ route('cart.store') }}" method="POST" class="flex gap-4 items-end">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <div>
                            <label for="quantity" class="block text-gray-700 font-semibold mb-2">Jumlah</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $book->stock }}"
                                class="w-24 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 font-semibold">
                            Tambah ke Keranjang
                        </button>
                    </form>
                @else
                    <button disabled class="bg-gray-400 text-white px-6 py-2 rounded-lg font-semibold cursor-not-allowed">
                        Stok Habis
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 font-semibold">
                    Login untuk Membeli
                </a>
            @endauth
        </div>
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('books.index') }}" class="text-indigo-600 hover:underline">&larr; Kembali ke Daftar Buku</a>
</div>
@endsection