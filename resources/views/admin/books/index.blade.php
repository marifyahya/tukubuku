@extends('layouts.app')

@section('title', 'Kelola Buku - Admin TukuBuku')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Buku</h1>
            <p class="text-gray-500 mt-1">Daftar semua buku yang tersedia di toko.</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="bg-primary text-white px-6 py-2.5 rounded-xl hover:bg-primary/90 font-bold shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-2 transform active:scale-95">
            <i class="fas fa-plus"></i> Tambah Buku
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <i class="fas fa-check-circle text-green-500 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/50 text-gray-500 text-sm">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Buku</th>
                        <th class="px-6 py-4 text-left font-semibold">Kategori & Penulis</th>
                        <th class="px-6 py-4 text-left font-semibold">Harga</th>
                        <th class="px-6 py-4 text-left font-semibold">Stok & Rating</th>
                        <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($books as $book)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    @if($book->cover_image)
                                        <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="w-12 h-16 object-cover rounded-lg shadow-sm border border-gray-100">
                                    @else
                                        <div class="w-12 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 border border-gray-200">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                    <div class="max-w-[200px]">
                                        <p class="font-bold text-gray-900 text-sm line-clamp-2" title="{{ $book->title }}">{{ $book->title }}</p>
                                        <p class="text-xs text-gray-500 mt-1">ID: #{{ $book->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900 line-clamp-1">{{ $book->author }}</p>
                                <span class="inline-block mt-1 px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold">{{ $book->category ?? 'Umum' }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-800">
                                @rupiah($book->price)
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500">Stok</p>
                                        <p class="text-sm font-bold {{ $book->stock > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $book->stock }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Rating</p>
                                        <div class="flex items-center text-xs font-bold text-gray-800">
                                            <i class="fas fa-star text-yellow-400 mr-1"></i> {{ $book->rating }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.books.edit', $book) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all" onclick="return confirm('Hapus buku ini?')" title="Hapus">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                        <i class="fas fa-book text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="font-medium">Belum ada buku dalam katalog.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($books->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</div>
@endsection