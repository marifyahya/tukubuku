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
            @foreach($topBooks as $book)
                @include('partials.book-card', ['book' => $book])
            @endforeach
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
            <a href="{{ route('books.index') }}" class="text-primary font-bold hover:underline flex items-center gap-2 text-sm md:text-base">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div id="grid-new" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
            @foreach($newBooks as $book)
                @include('partials.book-card', ['book' => $book])
            @endforeach
        </div>
    </section>

    <!-- Section Rekomendasi -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Rekomendasi Untukmu</h2>
                <div class="w-20 h-1.5 bg-primary rounded-full mt-2"></div>
            </div>
            <a href="{{ route('books.index') }}" class="text-primary font-bold hover:underline flex items-center gap-2 text-sm md:text-base">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div id="grid-recom" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
            @foreach($recommendedBooks as $book)
                @include('partials.book-card', ['book' => $book])
            @endforeach
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
/**
 * TukuBuku - E-commerce App Logic
 */

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
                    <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center px-6 py-2.5 md:px-8 md:py-3.5 bg-white text-primary hover:bg-primary hover:text-white font-bold rounded-full transition-colors shadow-lg group text-sm md:text-base">
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
