@extends('layouts.admin')

@section('title', 'Kelola User - Admin TukuBuku')
@section('page-title', 'Kelola User')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Kelola User</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar pengguna yang terdaftar di sistem.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="bg-primary text-white px-5 py-2.5 rounded-xl hover:bg-primary/90 font-bold shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-2 transform active:scale-95 text-sm">
        <i class="fas fa-user-plus"></i> Tambah User
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-5 mb-6">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
        <!-- Search -->
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm">
        </div>

        <!-- Role Filter -->
        <div class="w-full md:w-44">
            <select name="role" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm font-medium text-gray-700">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>

        <!-- Sort -->
        <div class="w-full md:w-44">
            <select name="sort" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm font-medium text-gray-700">
                <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Terbaru</option>
                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nama A-Z</option>
            </select>
            <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-primary text-white px-5 py-2.5 rounded-xl hover:bg-primary/90 font-semibold transition-all text-sm flex items-center gap-2">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'role', 'sort']))
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 font-semibold transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-times"></i> Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50/50 text-gray-500 text-xs">
                <tr>
                    <th class="px-5 py-3.5 text-left font-semibold">User</th>
                    <th class="px-5 py-3.5 text-left font-semibold">
                        <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'email', 'direction' => request('sort') === 'email' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-primary transition-colors inline-flex items-center gap-1">
                            Email
                            @if(request('sort') === 'email')
                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                            @else
                                <i class="fas fa-sort text-gray-300"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-5 py-3.5 text-left font-semibold">Role</th>
                    <th class="px-5 py-3.5 text-left font-semibold">
                        <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('sort', 'created_at') === 'created_at' && request('direction', 'desc') === 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-primary transition-colors inline-flex items-center gap-1">
                            Tanggal Daftar
                            @if(request('sort', 'created_at') === 'created_at')
                                <i class="fas fa-sort-{{ request('direction', 'desc') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                            @else
                                <i class="fas fa-sort text-gray-300"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-5 py-3.5 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-primary/10 border border-primary/20 overflow-hidden flex-shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0ea5e9&color=fff&size=36" alt="">
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">{{ $user->name }}</p>
                                    <p class="text-[11px] text-gray-400">ID: #{{ $user->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-sm font-medium text-gray-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-5 py-3.5">
                            @if($user->role === 'admin')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-violet-100 text-violet-700 border border-violet-200 inline-flex items-center gap-1">
                                    <i class="fas fa-shield-alt text-[8px]"></i> ADMIN
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200 inline-flex items-center gap-1">
                                    <i class="fas fa-user text-[8px]"></i> USER
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-600">
                            {{ $user->created_at->format('d M Y') }}
                            <span class="text-gray-400 text-xs block">{{ $user->created_at->format('H:i') }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all" onclick="return confirm('Hapus user ini?')" title="Hapus">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="fas fa-users text-xl text-gray-400"></i>
                                </div>
                                <p class="font-medium text-sm">Tidak ada user ditemukan.</p>
                                @if(request()->hasAny(['search', 'role']))
                                    <a href="{{ route('admin.users.index') }}" class="text-primary text-sm font-semibold mt-2 hover:underline">Reset filter</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/30">
            {{ $users->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortSelect = document.querySelector('select[name="sort"]');
        const directionInput = document.querySelector('input[name="direction"]');
        if (sortSelect && directionInput) {
            sortSelect.addEventListener('change', function() {
                if (this.value === 'name') {
                    directionInput.value = 'asc';
                } else {
                    directionInput.value = 'desc';
                }
            });
        }
    });
</script>
@endpush
@endsection