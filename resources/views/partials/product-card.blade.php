<div class="group glass-card rounded-2xl overflow-hidden transition-all duration-500 card-3d relative border border-white/5 hover:border-brand-500/20">
    <!-- Image -->
    <a href="{{ route('products.show', $product) }}" class="block relative img-magnify aspect-square bg-dark-800">
        <img src="{{ $product->primary_image_url }}"
             alt="{{ $product->name }}"
             class="w-full h-full object-cover"
             loading="lazy">

        <!-- Overlay gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-dark-950/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

        <!-- Badges -->
        @if($product->is_on_sale)
            <span class="absolute top-3 left-3 px-3 py-1 text-xs font-bold bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-full shadow-lg shadow-red-500/30 animate-glow-ring">
                -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
            </span>
        @endif

        <!-- Wishlist Heart -->
        <div x-data="{ wishlisted: false }" class="absolute top-3 right-3 z-10">
            <button @click.prevent="
                fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ product_id: {{ $product->id }} })
                }).then(r => { if(r.status===401){window.location='/login';return;} return r.json(); }).then(d => { if(d) wishlisted = d.wishlisted; })"
                class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-300 backdrop-blur-sm"
                :class="wishlisted ? 'bg-red-500/90 text-white shadow-lg shadow-red-500/40 scale-110' : 'bg-dark-900/60 text-gray-400 hover:text-red-400 hover:bg-dark-900/80'">
                <svg class="w-4 h-4" :fill="wishlisted ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>
        </div>

        @if($product->is_featured)
            <span class="absolute top-14 right-3 px-3 py-1 text-xs font-bold bg-gradient-to-r from-amber-500 to-yellow-400 text-dark-950 rounded-full shadow-lg">
                ⭐ Featured
            </span>
        @endif

        <!-- Quick Add -->
        <div class="absolute bottom-3 left-3 right-3 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 transform hover:scale-[1.02] transition-all duration-300 btn-glow backdrop-blur-sm">
                    ⚡ Quick Add
                </button>
            </form>
        </div>
    </a>

    <!-- Content -->
    <div class="p-5">
        <!-- Category -->
        <a href="{{ route('categories.show', $product->category) }}" class="text-xs text-brand-400 hover:text-brand-300 font-semibold uppercase tracking-wider transition-colors">
            {{ $product->category->name }}
        </a>

        <!-- Name -->
        <a href="{{ route('products.show', $product) }}" class="block mt-1.5">
            <h3 class="font-bold text-gray-200 group-hover:text-white transition-colors line-clamp-2 text-sm leading-snug">{{ $product->name }}</h3>
        </a>

        <!-- Rating -->
        <div class="flex items-center gap-1 mt-2">
            @for($i = 1; $i <= 5; $i++)
                <svg class="w-3.5 h-3.5 {{ $i <= round($product->average_rating) ? 'text-yellow-400' : 'text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
            <span class="text-xs text-gray-500 ml-1">({{ $product->approved_reviews_count ?? 0 }})</span>
        </div>

        <!-- Price -->
        <div class="flex items-center gap-2 mt-3">
            @if($product->is_on_sale)
                <span class="text-lg font-black text-white">${{ number_format($product->sale_price, 2) }}</span>
                <span class="text-sm text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
            @else
                <span class="text-lg font-black text-white">${{ number_format($product->price, 2) }}</span>
            @endif
        </div>

        <!-- Stock -->
        @if($product->stock < 10 && $product->stock > 0)
            <p class="text-xs text-amber-400 mt-2 font-medium">🔥 Only {{ $product->stock }} left!</p>
        @elseif($product->stock <= 0)
            <p class="text-xs text-red-400 mt-2 font-medium">Out of Stock</p>
        @endif
    </div>
</div>
