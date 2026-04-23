@extends('layouts.app')

@section('title', 'Profil Saya - TukuBuku')

@section('content')
<div class="bg-gray-50 py-8 min-h-[calc(100vh-200px)]">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <div class="w-full md:w-1/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-28">
                    <div class="p-6 text-center border-b border-gray-100">
                        <div class="w-24 h-24 rounded-full bg-gray-200 mx-auto mb-4 overflow-hidden border-4 border-white shadow-lg">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0ea5e9&color=fff&size=150" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                    <nav class="p-4 flex flex-col gap-1">
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl font-medium transition-colors">
                            <i class="fas fa-user w-5 text-center"></i> Profil Saya
                        </a>
                        <a href="{{ route('addresses.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary rounded-xl font-medium transition-colors">
                            <i class="fas fa-map-marker-alt w-5 text-center"></i> Buku Alamat
                        </a>
                        <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary rounded-xl font-medium transition-colors">
                            <i class="fas fa-shopping-bag w-5 text-center"></i> Pesanan Saya
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="block w-full">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-xl font-medium transition-colors w-full text-left">
                                <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Content -->
            <div class="w-full md:w-3/4 space-y-6">
                @if(session('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-xl flex items-center gap-3 border border-green-200">
                    <i class="fas fa-check-circle text-lg"></i>
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                
                @if($errors->any())
                <div class="bg-red-50 text-red-700 p-4 rounded-xl flex flex-col gap-2 border border-red-200">
                    <div class="flex items-center gap-3 font-semibold">
                        <i class="fas fa-exclamation-circle text-lg"></i>
                        <p>Terdapat kesalahan:</p>
                    </div>
                    <ul class="list-disc ml-8 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Edit Profile Form -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-user-edit text-primary"></i> Ubah Detail Profil
                    </h2>
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" required>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" required>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-primary text-white px-8 py-2.5 rounded-xl font-bold hover:bg-primary/90 transition-colors shadow-lg shadow-primary/30">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Form -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-lock text-primary"></i> Ubah Password
                    </h2>
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6 mb-6">
                            <div>
                                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">Password Saat Ini</label>
                                <input type="password" id="current_password" name="current_password" class="w-full md:w-1/2 px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" required>
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                                <input type="password" id="password" name="password" class="w-full md:w-1/2 px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" required>
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full md:w-1/2 px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" required>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-gray-800 text-white px-8 py-2.5 rounded-xl font-bold hover:bg-gray-900 transition-colors shadow-lg shadow-gray-800/30">
                                Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
