@extends('layouts.app')

@section('title', 'Kelola Buku - Admin Toko Buku')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Kelola Buku</h1>
    <a href="{{ route('admin.books.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
        Tambah Buku
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left">ID</th>
                <th class="px-6 py-3 text-left">Judul</th>
                <th class="px-6 py-3 text-left">Penulis</th>
                <th class="px-6 py-3 text-left">Harga</th>
                <th class="px-6 py-3 text-left">Stok</th>
                <th class="px-6 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $book)
                <tr class="border-t">
                    <td class="px-6 py-4">{{ $book->id }}</td>
                    <td class="px-6 py-4">{{ $book->title }}</td>
                    <td class="px-6 py-4">{{ $book->author }}</td>
                    <td class="px-6 py-4">@rupiah($book->price)</td>
                    <td class="px-6 py-4">{{ $book->stock }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.books.edit', $book) }}" class="text-indigo-600 hover:underline mr-2">Edit</a>
                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada buku.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $books->links() }}
</div>
@endsection