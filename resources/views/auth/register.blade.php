@extends('layouts.app')

@section('title', 'Register - TukuBuku')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-20 flex justify-center items-center">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Buat Akun Baru</h2>
            <p class="text-gray-500">Bergabunglah dengan komunitas pembaca TukuBuku.</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="name" class="block text-gray-700 font-semibold mb-2 text-sm">Nama Lengkap</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('name') border-red-500 @enderror"
                        placeholder="John Doe" required>
                </div>
                @error('name')
                    <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="email" class="block text-gray-700 font-semibold mb-2 text-sm">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('email') border-red-500 @enderror"
                        placeholder="nama@email.com" required>
                </div>
                @error('email')
                    <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="password" class="block text-gray-700 font-semibold mb-2 text-sm">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="password" id="password"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('password') border-red-500 @enderror"
                        placeholder="Minimal 8 karakter" required minlength="8">
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2 text-sm">Konfirmasi Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-check-circle text-gray-400"></i>
                    </div>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                        placeholder="Ulangi password" required>
                </div>
            </div>

            <button type="submit" class="w-full bg-primary text-white py-3 rounded-xl hover:bg-primary/90 font-bold shadow-lg shadow-primary/30 transition-all transform active:scale-95">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center mt-6 text-gray-600 text-sm">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection