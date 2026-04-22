@extends('layouts.app')

@section('title', 'Edit User - Admin TukuBuku')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.users.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-primary hover:shadow-md transition-all border border-gray-200">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit User</h1>
                <p class="text-gray-500 text-sm mt-1">Ubah informasi akun "{{ $user->name }}".</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-gray-700 font-semibold mb-2 text-sm">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('name') border-red-500 @enderror"
                            required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-gray-700 font-semibold mb-2 text-sm">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('email') border-red-500 @enderror"
                            required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="password" class="block text-gray-700 font-semibold mb-2 text-sm">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('password') border-red-500 @enderror"
                            minlength="8">
                        <p class="text-xs text-gray-500 mt-2"><i class="fas fa-info-circle text-primary"></i> Kosongkan jika tidak ingin mengubah password.</p>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition-colors text-center">
                        Batal
                    </a>
                    <button type="submit" class="bg-primary text-white px-8 py-2.5 rounded-xl hover:bg-primary/90 font-bold shadow-lg shadow-primary/30 transition-all transform active:scale-95 text-center">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection