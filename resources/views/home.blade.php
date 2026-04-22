@extends('layouts.app')

@section('title', 'TukuBuku - Temukan Buku Favoritmu')

@section('content')
<!-- Banner Slider (Hero Section) -->
<section class="relative h-[300px] md:h-[500px] bg-gray-900 overflow-hidden">
    <div id="slider-wrapper" class="flex h-full transition-transform duration-700 ease-in-out">
        <!-- Slides will be injected here by JS -->
    </div>
    
    <!-- Slider Controls -->
    <div class="absolute inset-y-0 left-0 flex items-center pl-4">
        <button id="prev-slide" class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/20 backdrop-blur-md text-white hover:bg-white hover:text-primary transition-all flex items-center justify-center">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
        <button id="next-slide" class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/20 backdrop-blur-md text-white hover:bg-white hover:text-primary transition-all flex items-center justify-center">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    
    <!-- Slider Indicators -->
    <div id="slider-indicators" class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3">
        <!-- Indicators will be injected here by JS -->
    </div>
</section>

<!-- Main Page Content -->
<div class="container mx-auto px-4 py-12">
    
    <!-- Section Produk Terlaris -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Produk Terlaris</h2>
                <div class="w-20 h-1.5 bg-primary rounded-full mt-2"></div>
            </div>
            <a href="#" class="text-primary font-bold hover:underline flex items-center gap-2 text-sm md:text-base">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div id="grid-top" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
            <!-- Product cards will be injected here by JS -->
        </div>
    </section>

    <!-- Section Banner Promo Sederhana -->
    <section class="mb-16 bg-primary/10 rounded-3xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="max-w-xl text-center md:text-left">
            <span class="inline-block py-1 px-3 rounded-full bg-primary text-white text-[10px] font-bold uppercase tracking-wider mb-4">Member Only</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-4 leading-tight">Dapatkan Diskon Tambahan 15% untuk Member Baru!</h2>
            <p class="text-gray-600 mb-8">Bergabunglah dengan komunitas TukuBuku dan nikmati berbagai keuntungan eksklusif setiap harinya.</p>
            <a href="{{ route('register') }}" class="inline-block bg-primary text-white px-8 py-3.5 rounded-full font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all transform active:scale-95">
                Daftar Sekarang
            </a>
        </div>
        <div class="relative hidden lg:block">
            <div class="w-64 h-64 bg-primary/20 rounded-full absolute -top-10 -right-10 blur-3xl"></div>
            <img src="https://placehold.co/400x400/0ea5e9/ffffff?text=Promo+Member" alt="Promo" class="relative z-10 w-72 h-72 object-cover rounded-2xl shadow-2xl rotate-3">
        </div>
    </section>

    <!-- Section Produk Terbaru -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Produk Terbaru</h2>
                <div class="w-20 h-1.5 bg-primary rounded-full mt-2"></div>
            </div>
            <a href="#" class="text-primary font-bold hover:underline flex items-center gap-2 text-sm md:text-base">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div id="grid-new" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
            <!-- Product cards will be injected here by JS -->
        </div>
    </section>

    <!-- Section Rekomendasi -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Rekomendasi Untukmu</h2>
                <div class="w-20 h-1.5 bg-primary rounded-full mt-2"></div>
            </div>
            <a href="#" class="text-primary font-bold hover:underline flex items-center gap-2 text-sm md:text-base">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div id="grid-recom" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
            <!-- Product cards will be injected here by JS -->
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
/**
 * TukuBuku - E-commerce App Logic
 */

// Dummy Data untuk Produk
const products = [
    { id: 1, title: "Seni Berpikir Stoik", author: "Fahruddin Faiz", price: 75000, oldPrice: 95000, rating: 4.8, category: "top", image: "https://placehold.co/400x600/e0f2fe/0ea5e9?text=Seni+Berpikir+Stoik" },
    { id: 2, title: "Atomic Habits", author: "James Clear", price: 120000, oldPrice: null, rating: 4.9, category: "top", image: "https://placehold.co/400x600/fef08a/ca8a04?text=Atomic+Habits" },
    { id: 3, title: "Filosofi Teras", author: "Henry Manampiring", price: 98000, oldPrice: 110000, rating: 4.7, category: "top", image: "https://placehold.co/400x600/dcfce7/16a34a?text=Filosofi+Teras" },
    { id: 4, title: "Laskar Pelangi", author: "Andrea Hirata", price: 85000, oldPrice: null, rating: 4.8, category: "top", image: "https://placehold.co/400x600/fecdd3/e11d48?text=Laskar+Pelangi" },
    { id: 5, title: "Bumi", author: "Tere Liye", price: 105000, oldPrice: 125000, rating: 4.6, category: "top", image: "https://placehold.co/400x600/e0e7ff/4f46e5?text=Bumi" },
    
    { id: 6, title: "Cantik Itu Luka", author: "Eka Kurniawan", price: 110000, oldPrice: null, rating: 4.5, category: "new", image: "https://placehold.co/400x600/ffedd5/ea580c?text=Cantik+Itu+Luka" },
    { id: 7, title: "The Psychology of Money", author: "Morgan Housel", price: 135000, oldPrice: null, rating: 4.9, category: "new", image: "https://placehold.co/400x600/d1fae5/059669?text=Psychology+of+Money" },
    { id: 8, title: "Sapiens", author: "Yuval Noah Harari", price: 145000, oldPrice: 170000, rating: 4.8, category: "new", image: "https://placehold.co/400x600/fee2e2/dc2626?text=Sapiens" },
    { id: 9, title: "Negeri Para Bedebah", author: "Tere Liye", price: 95000, oldPrice: null, rating: 4.6, category: "new", image: "https://placehold.co/400x600/cffafe/0891b2?text=Negeri+Para+Bedebah" },
    { id: 10, title: "Home Coming", author: "Leila S. Chudori", price: 99000, oldPrice: 115000, rating: 4.7, category: "new", image: "https://placehold.co/400x600/f3e8ff/7e22ce?text=Home+Coming" },
    
    { id: 11, title: "Madre", author: "Dee Lestari", price: 88000, oldPrice: null, rating: 4.5, category: "recom", image: "https://placehold.co/400x600/fae8ff/c026d3?text=Madre" },
    { id: 12, title: "Deep Work", author: "Cal Newport", price: 115000, oldPrice: 130000, rating: 4.8, category: "recom", image: "https://placehold.co/400x600/e0f2fe/0284c7?text=Deep+Work" },
    { id: 13, title: "Laut Bercerita", author: "Leila S. Chudori", price: 125000, oldPrice: null, rating: 4.9, category: "recom", image: "https://placehold.co/400x600/ffedd5/c2410c?text=Laut+Bercerita" },
    { id: 14, title: "Hujan", author: "Tere Liye", price: 92000, oldPrice: 105000, rating: 4.7, category: "recom", image: "https://placehold.co/400x600/dbeafe/2563eb?text=Hujan" },
    { id: 15, title: "Sebuah Seni untuk Bersikap Bodo Amat", author: "Mark Manson", price: 105000, oldPrice: null, rating: 4.6, category: "recom", image: "https://placehold.co/400x600/fef9c3/a16207?text=Bersikap+Bodo+Amat" }
];

// Format Rupiah
const formatRupiah = (angka) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka);
};

// Render Product Cards
function createProductCard(product) {
    const discountBadge = product.oldPrice 
        ? `<div class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-md z-10 shadow-sm">
            -${Math.round((product.oldPrice - product.price) / product.oldPrice * 100)}%
           </div>` 
        : '';
        
    const oldPriceHTML = product.oldPrice 
        ? `<span class="text-[10px] text-gray-400 line-through">${formatRupiah(product.oldPrice)}</span>` 
        : '';

    return `
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="relative w-full h-[180px] md:h-[240px] bg-gray-100 overflow-hidden">
                ${discountBadge}
                <img src="${product.image}" alt="${product.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button class="bg-white text-primary rounded-full w-10 h-10 flex items-center justify-center shadow-lg hover:bg-primary hover:text-white transition-colors transform translate-y-4 group-hover:translate-y-0" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <div class="p-4 flex flex-col h-[140px] md:h-[160px]">
                <p class="text-[10px] text-gray-500 mb-1">${product.author}</p>
                <h3 class="font-bold text-gray-800 text-xs md:text-sm leading-tight mb-1 line-clamp-2 hover:text-primary cursor-pointer transition-colors">${product.title}</h3>
                
                <div class="flex items-center gap-1 mb-2">
                    <i class="fas fa-star text-yellow-400 text-[10px]"></i>
                    <span class="text-[10px] font-medium text-gray-600">${product.rating}</span>
                </div>
                
                <div class="mt-auto flex items-end justify-between">
                    <div>
                        ${oldPriceHTML}
                        <div class="font-bold text-primary text-xs md:text-base leading-none">${formatRupiah(product.price)}</div>
                    </div>
                    <button onclick="addToCart(${product.id})" class="w-8 h-8 rounded-full bg-blue-50 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-colors shadow-sm">
                        <i class="fas fa-cart-plus text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

function renderProducts() {
    const gridTop = document.getElementById('grid-top');
    const gridNew = document.getElementById('grid-new');
    const gridRecom = document.getElementById('grid-recom');

    if(gridTop) gridTop.innerHTML = products.filter(p => p.category === 'top').map(createProductCard).join('');
    if(gridNew) gridNew.innerHTML = products.filter(p => p.category === 'new').map(createProductCard).join('');
    if(gridRecom) gridRecom.innerHTML = products.filter(p => p.category === 'recom').map(createProductCard).join('');
}

// Keranjang Belanja Sederhana
let cartItems = 0;
function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if(product) {
        cartItems++;
        const cartCountEl = document.getElementById('cart-count');
        if (cartCountEl) {
            cartCountEl.innerText = cartItems;
            
            // Animasi pop sederhana
            const cartIcon = cartCountEl.parentElement;
            cartIcon.classList.add('scale-125', 'text-primary');
            setTimeout(() => {
                cartIcon.classList.remove('scale-125', 'text-primary');
            }, 200);
        }
    }
}

// Banner Slider Logic
const slidesData = [
    {
        image: "https://placehold.co/1200x500/0ea5e9/ffffff?text=Promo+Spesial+Buku+Fiksi",
        title: "Promo Spesial Buku Fiksi",
        desc: "Diskon hingga 50% untuk koleksi novel best seller bulan ini.",
        cta: "Belanja Sekarang"
    },
    {
        image: "https://placehold.co/1200x500/10b981/ffffff?text=Pengetahuan+Tanpa+Batas",
        title: "Pengetahuan Tanpa Batas",
        desc: "Buku sains, teknologi, dan self-improvement untuk kembangkan potensimu.",
        cta: "Eksplorasi"
    },
    {
        image: "https://placehold.co/1200x500/f43f5e/ffffff?text=Cuci+Gudang+Akhir+Tahun",
        title: "Cuci Gudang Buku Favorit",
        desc: "Dapatkan harga miring untuk buku-buku incaranmu. Stok terbatas!",
        cta: "Lihat Katalog"
    }
];

let currentSlide = 0;
let slideInterval;

function renderSlider() {
    const sliderWrapper = document.getElementById('slider-wrapper');
    const indicatorsWrapper = document.getElementById('slider-indicators');
    
    if(!sliderWrapper) return;

    sliderWrapper.innerHTML = slidesData.map(slide => `
        <div class="slide min-w-full h-full relative">
            <img src="${slide.image}" class="w-full h-full object-cover" alt="${slide.title}">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 to-transparent flex items-center">
                <div class="container mx-auto px-8 md:px-16 lg:w-1/2">
                    <span class="inline-block py-1 px-3 rounded-full bg-white/20 backdrop-blur-sm text-white text-[10px] font-bold mb-4 tracking-wider uppercase border border-white/30">Terbatas</span>
                    <h2 class="text-2xl md:text-5xl font-bold text-white mb-4 leading-tight">${slide.title}</h2>
                    <p class="text-gray-200 text-xs md:text-lg mb-8 max-w-lg">${slide.desc}</p>
                    <a href="#" class="inline-flex items-center justify-center px-6 py-2.5 md:px-8 md:py-3.5 bg-white text-primary hover:bg-primary hover:text-white font-bold rounded-full transition-colors shadow-lg group text-sm md:text-base">
                        ${slide.cta}
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    `).join('');

    if (indicatorsWrapper) {
        indicatorsWrapper.innerHTML = slidesData.map((_, index) => `
            <button onclick="goToSlide(${index})" class="w-2 h-2 md:w-3 md:h-3 rounded-full transition-all ${index === 0 ? 'bg-white w-6 md:w-8' : 'bg-white/50 hover:bg-white/80'}"></button>
        `).join('');
    }
}

function updateSlider() {
    const sliderWrapper = document.getElementById('slider-wrapper');
    if(sliderWrapper) {
        sliderWrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
        
        // Update Indicators
        const indicatorsWrapper = document.getElementById('slider-indicators');
        if (indicatorsWrapper) {
            const indicators = indicatorsWrapper.children;
            Array.from(indicators).forEach((ind, index) => {
                if(index === currentSlide) {
                    ind.className = 'w-6 md:w-8 h-2 md:h-3 rounded-full transition-all bg-white';
                } else {
                    ind.className = 'w-2 h-2 md:w-3 md:h-3 rounded-full transition-all bg-white/50 hover:bg-white/80';
                }
            });
        }
    }
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % slidesData.length;
    updateSlider();
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + slidesData.length) % slidesData.length;
    updateSlider();
}

function goToSlide(index) {
    currentSlide = index;
    updateSlider();
    resetInterval();
}

function startInterval() {
    slideInterval = setInterval(nextSlide, 5000); // 5 seconds
}

function resetInterval() {
    clearInterval(slideInterval);
    startInterval();
}

// Event Listeners for Slider Controls
document.addEventListener('DOMContentLoaded', () => {
    // Inisialisasi Konten
    renderSlider();
    renderProducts();
    
    // Setup event listener untuk kontrol slider jika elemen ada
    const btnNext = document.getElementById('next-slide');
    const btnPrev = document.getElementById('prev-slide');
    
    if(btnNext) {
        btnNext.addEventListener('click', () => {
            nextSlide();
            resetInterval();
        });
    }
    
    if(btnPrev) {
        btnPrev.addEventListener('click', () => {
            prevSlide();
            resetInterval();
        });
    }
    
    // Mulai auto-play slider
    startInterval();
});
</script>
@endpush
