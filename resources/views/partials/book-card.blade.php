<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
    <div class="relative w-full h-[180px] md:h-[240px] bg-gray-100 overflow-hidden">
        {{-- Discount badge logic removed as no old_price in DB --}}
        <img src="{{ $book->cover_image ? Storage::url($book->cover_image) : 'https://placehold.co/400x600/e0f2fe/0ea5e9?text=' . urlencode($book->title) }}" 
             alt="{{ $book->title }}" 
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <a href="{{ route('books.show', $book->slug) }}" class="bg-white text-primary rounded-full w-10 h-10 flex items-center justify-center shadow-lg hover:bg-primary hover:text-white transition-colors transform translate-y-4 group-hover:translate-y-0" title="Lihat Detail">
                <i class="fas fa-eye"></i>
            </a>
        </div>
    </div>
    <div class="p-4 flex flex-col h-[140px] md:h-[160px]">
        <p class="text-[10px] text-gray-500 mb-1">{{ $book->author }}</p>
        <h3 class="font-bold text-gray-800 text-xs md:text-sm leading-tight mb-1 line-clamp-2 hover:text-primary cursor-pointer transition-colors">
            <a href="{{ route('books.show', $book->slug) }}">{{ $book->title }}</a>
        </h3>
        
        <div class="flex items-center gap-1 mb-2">
            <i class="fas fa-star text-yellow-400 text-[10px]"></i>
            <span class="text-[10px] font-medium text-gray-600">{{ number_format($book->rating, 1) }}</span>
        </div>
        
        <div class="mt-auto flex items-end justify-between">
            <div>
                <div class="font-bold text-primary text-xs md:text-base leading-none">@rupiah($book->price)</div>
            </div>
            <form action="{{ route('cart.store') }}" method="POST">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="w-8 h-8 rounded-full bg-blue-50 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-colors shadow-sm">
                    <i class="fas fa-cart-plus text-xs"></i>
                </button>
            </form>
        </div>
    </div>
</div>
