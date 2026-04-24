<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - TukuBuku')</title>

    <!-- Assets (Tailwind CSS & JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .sidebar-link {
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.08);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        /* Mobile sidebar overlay */
        .sidebar-overlay {
            transition: opacity 0.3s ease;
        }

        .sidebar-mobile {
            transition: transform 0.3s ease;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-900">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-sidebar flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 sidebar-mobile">
            <!-- Logo Section -->
            <div class="h-16 flex items-center px-6 border-b border-white/5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-primary rounded-lg flex items-center justify-center text-white shadow-lg shadow-primary/30">
                        <i class="fas fa-book-open text-sm"></i>
                    </div>
                    <span class="text-lg font-bold text-white tracking-tight">Tuku<span class="text-primary">Buku</span></span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 py-6 px-3 sidebar-scroll overflow-y-auto">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-3">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-1 {{ request()->routeIs('admin.dashboard') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-th-large text-sm"></i>
                    </div>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-1 {{ request()->routeIs('admin.orders.*') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.orders.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-shopping-bag text-sm"></i>
                    </div>
                    <span>Kelola Pesanan</span>
                    @php
                        $pendingCount = \App\Models\Order::where('status', \App\Enums\OrderStatus::UNPAID)->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-rose-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full">{{ $pendingCount > 99 ? '99+' : $pendingCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.books.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-1 {{ request()->routeIs('admin.books.*') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.books.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-book text-sm"></i>
                    </div>
                    <span>Kelola Buku</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-1 {{ request()->routeIs('admin.users.*') ? 'active text-white' : 'text-gray-400 hover:text-white' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.users.*') ? 'bg-white/20' : 'bg-white/5' }}">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                    <span>Kelola User</span>
                </a>

                <div class="border-t border-white/5 my-4 mx-3"></div>

                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-3">Lainnya</p>

                <a href="{{ route('home') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-1 text-gray-400 hover:text-white" target="_blank">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/5">
                        <i class="fas fa-external-link-alt text-sm"></i>
                    </div>
                    <span>Lihat Toko</span>
                </a>
            </nav>

            <!-- User Section -->
            <div class="p-4 border-t border-white/5">
                <div class="flex items-center gap-3 px-2">
                    <div class="w-9 h-9 rounded-full bg-primary/20 overflow-hidden border border-primary/30 flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0ea5e9&color=fff&size=36" alt="">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">Administrator</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:bg-rose-500/20 hover:text-rose-400 transition-all" title="Logout">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Sidebar Overlay (mobile) -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Main Content Area -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <!-- Top Navbar -->
            <header class="sticky top-0 z-30 h-16 bg-white/90 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-4 lg:px-8">
                <div class="flex items-center gap-4">
                    <!-- Mobile menu toggle -->
                    <button id="sidebar-toggle" class="lg:hidden w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition-colors" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Breadcrumb -->
                    <div class="hidden sm:flex items-center gap-2 text-sm">
                        <span class="text-gray-400">Admin</span>
                        <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                        <span class="text-gray-700 font-semibold">@yield('page-title', 'Dashboard')</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Notifications placeholder -->
                    <button class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition-colors relative">
                        <i class="far fa-bell text-lg"></i>
                        @if($pendingCount > 0)
                            <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full"></span>
                        @endif
                    </button>

                    <!-- User dropdown -->
                    <div class="relative">
                        <button id="admin-profile-btn" class="flex items-center gap-2 px-3 py-1.5 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-primary/10 overflow-hidden border border-primary/20">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0ea5e9&color=fff&size=32" alt="">
                            </div>
                            <span class="hidden md:block text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div id="admin-profile-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 transform opacity-0 scale-95 transition-all duration-200 origin-top-right">
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary transition-colors">
                                <i class="fas fa-store w-5"></i> Lihat Toko
                            </a>
                            <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary transition-colors">
                                <i class="fas fa-user w-5"></i> Akun Saya
                            </a>
                            <div class="border-t border-gray-50 my-1"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-sign-out-alt w-5"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mx-4 lg:mx-8 mt-4">
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3 rounded-xl flex items-center gap-3 shadow-sm">
                        <i class="fas fa-check-circle text-emerald-500"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-4 lg:mx-8 mt-4">
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-5 py-3 rounded-xl flex items-center gap-3 shadow-sm">
                        <i class="fas fa-exclamation-circle text-rose-500"></i>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-rose-400 hover:text-rose-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-8">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="px-4 lg:px-8 py-4 border-t border-gray-100 text-center text-sm text-gray-400">
                &copy; {{ date('Y') }} TukuBuku Admin Panel. All rights reserved.
            </footer>
        </div>
    </div>

    @stack('scripts')

    <script>
        // Sidebar toggle for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        // Admin profile dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('admin-profile-btn');
            const menu = document.getElementById('admin-profile-menu');

            if (btn && menu) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (menu.classList.contains('hidden')) {
                        menu.classList.remove('hidden');
                        setTimeout(() => {
                            menu.classList.remove('opacity-0', 'scale-95');
                            menu.classList.add('opacity-100', 'scale-100');
                        }, 10);
                    } else {
                        menu.classList.remove('opacity-100', 'scale-100');
                        menu.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => menu.classList.add('hidden'), 200);
                    }
                });

                document.addEventListener('click', function(e) {
                    if (!menu.contains(e.target) && !btn.contains(e.target)) {
                        if (!menu.classList.contains('hidden')) {
                            menu.classList.remove('opacity-100', 'scale-100');
                            menu.classList.add('opacity-0', 'scale-95');
                            setTimeout(() => menu.classList.add('hidden'), 200);
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>
