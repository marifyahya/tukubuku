@extends('layouts.app')

@section('title', 'Buku Alamat - TukuBuku')

@section('content')
<div class="bg-gray-50 py-8 min-h-[calc(100vh-200px)]">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <div class="w-full md:w-1/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-28">
                    <div class="p-6 text-center border-b border-gray-100">
                        <div class="w-24 h-24 rounded-full bg-gray-200 mx-auto mb-4 overflow-hidden border-4 border-white shadow-lg">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0ea5e9&color=fff&size=150" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <nav class="p-4 flex flex-col gap-1">
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary rounded-xl font-medium transition-colors">
                            <i class="fas fa-user w-5 text-center"></i> Profil Saya
                        </a>
                        <a href="{{ route('addresses.index') }}" class="flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl font-medium transition-colors">
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
                        <p>Terdapat kesalahan saat memproses formulir alamat.</p>
                    </div>
                </div>
                @endif

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-primary"></i> Daftar Alamat
                        </h2>
                        <button onclick="document.getElementById('addAddressModal').classList.remove('hidden')" class="bg-primary text-white px-6 py-2.5 rounded-xl font-bold hover:bg-primary/90 transition-colors shadow-lg shadow-primary/30 flex items-center gap-2">
                            <i class="fas fa-plus"></i> Tambah Alamat Baru
                        </button>
                    </div>

                    @if($addresses->isEmpty())
                        <div class="text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 shadow-sm">
                                <i class="fas fa-map-marked-alt text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Alamat</h3>
                            <p class="text-gray-500 mb-6">Anda belum menambahkan alamat pengiriman apapun.</p>
                            <button onclick="document.getElementById('addAddressModal').classList.remove('hidden')" class="inline-block bg-white text-primary border border-primary px-6 py-2.5 rounded-xl font-bold hover:bg-primary/5 transition-colors">
                                Tambah Alamat Sekarang
                            </button>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($addresses as $address)
                            <div class="border {{ $address->is_primary ? 'border-primary bg-primary/5' : 'border-gray-200 hover:border-gray-300' }} rounded-xl p-5 transition-colors relative group">
                                @if($address->is_primary)
                                <div class="absolute -top-3 -right-3 bg-primary text-white text-[10px] font-bold px-3 py-1 rounded-full border-2 border-white shadow-sm flex items-center gap-1 z-10">
                                    <i class="fas fa-check-circle"></i> Alamat Utama
                                </div>
                                @endif

                                <div class="flex flex-col md:flex-row justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="font-bold text-gray-900">{{ $address->full_name }}</h4>
                                            <span class="text-gray-300">|</span>
                                            <span class="text-sm font-medium text-gray-600">{{ $address->phone_number }}</span>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-1 leading-relaxed">{{ $address->full_address }}</p>
                                        @if($address->landmark)
                                        <p class="text-gray-500 text-sm flex items-center gap-1.5"><i class="fas fa-map-pin text-gray-400"></i> {{ $address->landmark }}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-row md:flex-col items-center md:items-end justify-end gap-3 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-4 mt-4 md:mt-0">
                                        <div class="flex gap-2 w-full md:w-auto">
                                            <button onclick="openEditModal({{ $address->id }}, {{ json_encode($address) }})" class="flex-1 md:flex-none text-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-bold transition-colors">
                                                Ubah
                                            </button>
                                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" class="flex-1 md:flex-none">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full px-4 py-2 border border-red-200 text-red-600 hover:bg-red-50 rounded-lg text-sm font-bold transition-colors" onclick="return confirm('Hapus alamat ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                        @if(!$address->is_primary)
                                        <form action="{{ route('addresses.primary', $address->id) }}" method="POST" class="w-full md:w-auto mt-2">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-2 border border-primary text-primary hover:bg-primary/5 rounded-lg text-sm font-bold transition-colors">
                                                Jadikan Utama
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Alamat -->
<div id="addAddressModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white rounded-t-2xl z-10">
            <h3 class="text-xl font-bold text-gray-800">Tambah Alamat Baru</h3>
            <button onclick="document.getElementById('addAddressModal').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form action="{{ route('addresses.store') }}" method="POST" id="addAddressForm">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="full_name" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Telepon</label>
                        <input type="text" name="phone_number" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap</label>
                        <textarea name="full_address" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-none" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Patokan / Blok / Unit (Opsional)</label>
                        <input type="text" name="landmark" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>
                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" id="is_primary" name="is_primary" value="1" class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <label for="is_primary" class="text-sm text-gray-700 select-none">Jadikan sebagai alamat utama</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl sticky bottom-0 flex justify-end gap-3">
            <button onclick="document.getElementById('addAddressModal').classList.add('hidden')" class="px-6 py-2.5 font-bold text-gray-600 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
            <button type="submit" form="addAddressForm" class="px-6 py-2.5 font-bold bg-primary text-white hover:bg-primary/90 rounded-xl shadow-md shadow-primary/20 transition-all">Simpan Alamat</button>
        </div>
    </div>
</div>

<!-- Modal Ubah Alamat -->
<div id="editAddressModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white rounded-t-2xl z-10">
            <h3 class="text-xl font-bold text-gray-800">Ubah Alamat</h3>
            <button onclick="document.getElementById('editAddressModal').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form method="POST" id="editAddressForm">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="full_name" id="edit_full_name" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Telepon</label>
                        <input type="text" name="phone_number" id="edit_phone_number" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap</label>
                        <textarea name="full_address" id="edit_full_address" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-none" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Patokan / Blok / Unit (Opsional)</label>
                        <input type="text" name="landmark" id="edit_landmark" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>
                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" id="edit_is_primary" name="is_primary" value="1" class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <label for="edit_is_primary" class="text-sm text-gray-700 select-none">Jadikan sebagai alamat utama</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl sticky bottom-0 flex justify-end gap-3">
            <button onclick="document.getElementById('editAddressModal').classList.add('hidden')" class="px-6 py-2.5 font-bold text-gray-600 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
            <button type="submit" form="editAddressForm" class="px-6 py-2.5 font-bold bg-primary text-white hover:bg-primary/90 rounded-xl shadow-md shadow-primary/20 transition-all">Simpan Perubahan</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openEditModal(id, address) {
        const modal = document.getElementById('editAddressModal');
        const form = document.getElementById('editAddressForm');
        
        form.action = `/addresses/${id}`;
        
        document.getElementById('edit_full_name').value = address.full_name;
        document.getElementById('edit_phone_number').value = address.phone_number;
        document.getElementById('edit_full_address').value = address.full_address;
        document.getElementById('edit_landmark').value = address.landmark || '';
        document.getElementById('edit_is_primary').checked = address.is_primary;
        
        modal.classList.remove('hidden');
    }
</script>
@endpush
