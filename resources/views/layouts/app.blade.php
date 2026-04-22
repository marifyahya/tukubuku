<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TukuBuku - Toko Buku Online Terpercaya')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0ea5e9',
                        secondary: '#64748b',
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(8px);
            background-color: rgba(255, 255, 255, 0.9);
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen">

    <!-- Header (Sticky) -->
    <header class="sticky-header border-b border-gray-100 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-20 gap-4">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/30">
                        <i class="fas fa-book-open text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-gray-800">Tuku<span class="text-primary">Buku</span></span>
                </a>

                <!-- Search Bar (Desktop) -->
                <div class="hidden md:flex flex-grow max-w-xl relative">
                    <input type="text" placeholder="Cari judul buku, penulis, atau ISBN..." 
                        class="w-full bg-gray-100 border-none rounded-full py-2.5 pl-12 pr-4 focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all outline-none">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>

                <!-- Navigation & Icons -->
                <nav class="flex items-center gap-2 md:gap-6">
                    <div class="hidden lg:flex items-center gap-6 mr-4 font-medium text-gray-600">
                        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                        <a href="#" class="hover:text-primary transition-colors">Kategori</a>
                        <a href="#" class="hover:text-primary transition-colors">Promo</a>
                    </div>

                    <div class="flex items-center gap-1 md:gap-3">
                        <button class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-600 transition-colors relative">
                            <i class="far fa-bell text-lg"></i>
                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                        <a href="{{ route('wishlist.index') }}" class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-600 transition-colors relative">
                            <i class="far fa-heart text-lg"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">
                                {{ Auth::check() ? Auth::user()->wishlists()->count() : 0 }}
                            </span>
                        </a>
                        <a href="{{ route('cart.index') }}" class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-600 transition-colors relative">
                            <i class="fas fa-shopping-cart text-lg"></i>
                            <span id="cart-count" class="absolute -top-1 -right-1 bg-primary text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">
                                {{ Auth::check() ? Auth::user()->carts->sum('quantity') : 0 }}
                            </span>
                        </a>
                    </div>

                    <div class="h-8 w-[1px] bg-gray-200 mx-2 hidden md:block"></div>

                    @auth
                        <div class="flex items-center gap-3">
                            <button class="flex items-center gap-2 group">
                                <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden border-2 border-transparent group-hover:border-primary transition-all">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0ea5e9&color=fff" alt="Profile">
                                </div>
                                <span class="hidden md:block font-semibold text-sm text-gray-700">{{ Auth::user()->name }}</span>
                            </button>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-red-500 transition-colors" title="Logout">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden md:block font-bold text-gray-700 hover:text-primary transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-primary text-white px-6 py-2.5 rounded-full font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all transform active:scale-95">Daftar</a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <div>
                    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-6">
                        <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white">
                            <i class="fas fa-book-open text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold tracking-tight">Tuku<span class="text-primary">Buku</span></span>
                    </a>
                    <p class="text-gray-400 leading-relaxed mb-6">
                        TukuBuku adalah destinasi utama Anda untuk menemukan literatur terbaik. Kami menghadirkan ribuan koleksi buku dari berbagai genre untuk mencerahkan hari-hari Anda.
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-6">Tautan Cepat</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Semua Buku</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Penulis Populer</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Promo Spesial</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Blog & Berita</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-6">Bantuan</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li><a href="#" class="hover:text-primary transition-colors">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Cara Belanja</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Kebijakan Pengembalian</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Lacak Pesanan</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-6">Kontak Kami</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt mt-1.5 text-primary"></i>
                            <span>Jl. Pendidikan No. 123, Jakarta Selatan, Indonesia 12345</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone-alt text-primary"></i>
                            <span>(021) 1234-5678</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-envelope text-primary"></i>
                            <span>halo@tukubuku.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-gray-800 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} TukuBuku. Seluruh hak cipta dilindungi undang-undang.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
