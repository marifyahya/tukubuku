@extends('layouts.admin')

@section('title', 'Tambah Buku - Admin TukuBuku')
@section('page-title', 'Tambah Buku')

@section('content')
<div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.books.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-primary hover:shadow-md transition-all border border-gray-200">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Buku Baru</h1>
                <p class="text-gray-500 text-sm mt-1">Masukkan detail buku untuk ditambahkan ke katalog.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-gray-700 font-semibold mb-2 text-sm">Judul Buku</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('title') border-red-500 @enderror"
                            placeholder="Contoh: Belajar Laravel untuk Pemula" required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="author" class="block text-gray-700 font-semibold mb-2 text-sm">Penulis</label>
                        <input type="text" name="author" id="author" value="{{ old('author') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('author') border-red-500 @enderror"
                            placeholder="Nama Penulis" required>
                        @error('author')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-gray-700 font-semibold mb-2 text-sm">Kategori</label>
                        <input type="text" name="category" id="category" value="{{ old('category') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('category') border-red-500 @enderror"
                            placeholder="Contoh: Programming">
                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-gray-700 font-semibold mb-2 text-sm">Harga (Rp)</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('price') border-red-500 @enderror"
                            placeholder="100000" required>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stock" class="block text-gray-700 font-semibold mb-2 text-sm">Stok Awal</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('stock') border-red-500 @enderror"
                            required>
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-gray-700 font-semibold mb-2 text-sm">Deskripsi Buku</label>
                        <textarea name="description" id="description" rows="5"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('description') border-red-500 @enderror"
                            placeholder="Tuliskan sinopsis atau deskripsi singkat buku ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="cover_image" class="block text-gray-700 font-semibold mb-2 text-sm">Sampul Buku</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center bg-gray-50 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500 mb-4">Upload gambar sampul buku (JPG, PNG). Maks 2MB.</p>
                            <input type="file" name="cover_image" id="cover_image" accept="image/*"
                                class="w-full max-w-xs mx-auto block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 focus:outline-none">
                        </div>
                        @error('cover_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('admin.books.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition-colors text-center">
                        Batal
                    </a>
                    <button type="submit" class="bg-primary text-white px-8 py-2.5 rounded-xl hover:bg-primary/90 font-bold shadow-lg shadow-primary/30 transition-all transform active:scale-95 text-center">
                        Simpan Buku
                    </button>
                </div>
            </form>
        </div>
</div>
@endsection