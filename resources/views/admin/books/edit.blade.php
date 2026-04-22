@extends('layouts.app')

@section('title', 'Edit Buku - Admin Toko Buku')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Buku</h1>

<div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="title" class="block text-gray-700 font-semibold mb-2">Judul</label>
                <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('title') border-red-500 @enderror"
                    required>
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="author" class="block text-gray-700 font-semibold mb-2">Penulis</label>
                <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('author') border-red-500 @enderror"
                    required>
                @error('author')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="price" class="block text-gray-700 font-semibold mb-2">Harga</label>
                <input type="number" name="price" id="price" value="{{ old('price', $book->price) }}" step="0.01"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('price') border-red-500 @enderror"
                    required>
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="stock" class="block text-gray-700 font-semibold mb-2">Stok</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', $book->stock) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('stock') border-red-500 @enderror"
                    required>
                @error('stock')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $book->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="cover_image" class="block text-gray-700 font-semibold mb-2">Sampul Buku</label>
                @if($book->cover_image)
                    <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="w-32 h-40 object-cover mb-2 rounded">
                @endif
                <input type="file" name="cover_image" id="cover_image" accept="image/*"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('cover_image') border-red-500 @enderror">
                <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah</p>
                @error('cover_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 font-semibold">
                Update
            </button>
            <a href="{{ route('admin.books.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 font-semibold">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection