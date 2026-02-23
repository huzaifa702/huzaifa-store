@extends('layouts.app')
@section('title', 'Huzaifa Store — Premium Shopping Experience')

@section('content')

    <!-- 3D Animated Hero Section -->
    <section class="hero-gradient relative overflow-hidden" x-data="heroSlider()" x-init="startAutoSlide()">
        <!-- Animated Particles -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="particle particle-cyan" style="top:12%; left:8%; animation-delay:0s; animation-duration:6s;"></div>
            <div class="particle particle-purple"
                style="top:25%; left:75%; animation-delay:1.5s; animation-duration:7s; width:4px; height:4px;"></div>
            <div class="particle particle-blue" style="top:60%; left:15%; animation-delay:3s; animation-duration:9s;"></div>
            <div class="particle particle-pink" style="top:40%; left:55%; animation-delay:0.5s; animation-duration:5s;">
            </div>
            <div class="particle particle-cyan"
                style="top:70%; left:85%; animation-delay:2s; animation-duration:8s; width:5px; height:5px;"></div>
            <div class="particle particle-gold"
                style="top:15%; left:45%; animation-delay:4s; animation-duration:10s; width:4px; height:4px;"></div>
            <div class="particle particle-purple" style="top:80%; left:30%; animation-delay:1s; animation-duration:7s;">
            </div>
            <div class="particle particle-pink" style="top:50%; left:90%; animation-delay:3.5s; animation-duration:6s;">
            </div>

            <!-- Morphing Orbs -->
            <div
                class="absolute top-20 left-10 w-48 h-48 bg-gradient-to-br from-brand-500/10 to-neon-cyan/5 animate-morph animate-float-slow blur-2xl">
            </div>
            <div class="absolute bottom-10 right-10 w-64 h-64 bg-gradient-to-br from-neon-purple/10 to-accent-500/5 animate-morph blur-3xl"
                style="animation-delay: -4s;"></div>
            <div class="absolute top-40 right-1/3 w-36 h-36 bg-gradient-to-br from-neon-pink/8 to-brand-400/5 animate-morph blur-2xl"
                style="animation-delay: -2s;"></div>
        </div>

        <!-- Grid Overlay -->
        <div class="absolute inset-0 opacity-[0.015] pointer-events-none"
            style="background-image: linear-gradient(rgba(255,255,255,.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.1) 1px, transparent 1px); background-size: 60px 60px;">
        </div>

        <div class="max-w-7xl mx-auto px-4 py-24 md:py-36 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="text-white space-y-6 relative" style="min-height: 280px;">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div x-show="current === index" x-transition:enter="transition ease-out duration-700"
                            x-transition:enter-start="opacity-0 transform translate-y-6"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" class="absolute inset-0">
                            <span
                                class="inline-block px-4 py-1.5 bg-brand-500/15 rounded-full text-sm font-medium border border-brand-400/20 text-brand-300 mb-4 animate-scale-in"
                                x-text="slide.badge"></span>
                            <h1 class="text-4xl md:text-6xl font-black leading-tight" x-html="slide.title"></h1>
                            <p class="text-lg text-gray-400 max-w-md leading-relaxed mt-4" x-text="slide.desc"></p>
                            <div class="flex gap-4 mt-8">
                                <a href="{{ route('products.index') }}"
                                    class="px-8 py-3.5 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-bold shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transform hover:scale-105 transition-all duration-300 btn-glow">
                                    Shop Now →
                                </a>
                                <a href="{{ route('products.index') }}?sort=newest"
                                    class="px-8 py-3.5 border border-white/10 text-white rounded-xl font-semibold hover:bg-white/5 transition-all duration-300 backdrop-blur-sm">
                                    New Arrivals
                                </a>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- 3D Product Image Showcase -->
                <div class="relative hidden md:flex items-center justify-center"
                    style="perspective: 1200px; min-height: 400px;">
                    <template x-for="(slide, index) in slides" :key="'img'+index">
                        <div x-show="current === index" x-transition:enter="transition ease-out duration-700"
                            x-transition:enter-start="opacity-0 transform scale-90 translate-x-12"
                            x-transition:enter-end="opacity-100 transform scale-100 translate-x-0"
                            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0 transform scale-90"
                            class="absolute inset-0 flex items-center justify-center">
                            <div class="w-80 h-80 mx-auto rounded-3xl overflow-hidden shadow-2xl shadow-brand-500/20 ring-1 ring-white/[0.08] card-3d"
                                @mousemove="tilt3D($event, $el)" @mouseleave="resetTilt($el)">
                                <img :src="slide.image" :alt="slide.badge" class="w-full h-full object-cover"
                                    loading="lazy">
                            </div>
                            <div
                                class="absolute -inset-8 bg-gradient-to-r from-brand-500/10 via-neon-cyan/5 to-neon-purple/10 rounded-full blur-3xl -z-10 animate-float-slow">
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Slider Dots -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2 z-20">
            <template x-for="(slide, index) in slides" :key="'dot'+index">
                <button @click="goTo(index)" class="transition-all duration-300 rounded-full"
                    :class="current === index ? 'w-8 h-2.5 bg-gradient-to-r from-brand-400 to-neon-cyan' : 'w-2.5 h-2.5 bg-white/20 hover:bg-white/40'">
                </button>
            </template>
        </div>
    </section>

    <!-- Animated Neon Divider -->
    <div class="neon-line w-full"></div>

    <!-- Animated Stats Counter Section -->
    <section class="py-16 gradient-section">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6" x-data="{ shown: false }" x-intersect.once="shown = true">
                @php
                    $stats = [
                        ['icon' => '📦', 'value' => '116', 'label' => 'Products', 'suffix' => '+', 'color' => 'from-brand-400 to-neon-cyan'],
                        ['icon' => '🏷️', 'value' => '6', 'label' => 'Categories', 'suffix' => '', 'color' => 'from-neon-purple to-neon-pink'],
                        ['icon' => '⭐', 'value' => '4.8', 'label' => 'Avg Rating', 'suffix' => '', 'color' => 'from-yellow-400 to-orange-500'],
                        ['icon' => '🚚', 'value' => '24', 'label' => 'Hour Delivery', 'suffix' => 'h', 'color' => 'from-emerald-400 to-teal-500'],
                    ];
                @endphp
                @foreach($stats as $i => $stat)
                    <div class="text-center glass-card rounded-2xl p-6 hover-rotate-3d" x-show="shown"
                        x-transition:enter="transition ease-out duration-700"
                        x-transition:enter-start="opacity-0 transform translate-y-8"
                        x-transition:enter-end="opacity-100 transform translate-y-0" style="transition-delay: {{ $i * 150 }}ms">
                        <div class="text-4xl mb-3 animate-float" style="animation-delay: {{ $i * 200 }}ms">{{ $stat['icon'] }}
                        </div>
                        <div class="text-3xl md:text-4xl font-black gradient-text bg-gradient-to-r {{ $stat['color'] }}"
                            style="background: linear-gradient(135deg, var(--tw-gradient-from), var(--tw-gradient-to)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            {{ $stat['value'] }}{{ $stat['suffix'] }}
                        </div>
                        <div class="text-gray-400 text-sm mt-1 font-medium">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Categories Grid -->
    <section class="py-20 relative">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <span
                    class="inline-block px-4 py-1 bg-brand-500/10 rounded-full text-brand-400 text-sm font-semibold border border-brand-500/20 mb-4">Browse
                    Categories</span>
                <h2 class="text-3xl md:text-5xl font-black gradient-text-shimmer">Shop by Category</h2>
                <p class="text-gray-400 mt-3 max-w-lg mx-auto">Explore our curated collections across 6 premium categories
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 stagger-children">
                @php
                    $categoryColors = [
                        'Electronics' => ['from-blue-500/20 to-indigo-600/20', 'hover:border-blue-500/40', 'from-blue-500 to-indigo-600'],
                        'Fashion' => ['from-pink-500/20 to-rose-600/20', 'hover:border-pink-500/40', 'from-pink-500 to-rose-600'],
                        'Home & Living' => ['from-amber-500/20 to-orange-600/20', 'hover:border-amber-500/40', 'from-amber-500 to-orange-600'],
                        'Sports' => ['from-emerald-500/20 to-green-600/20', 'hover:border-emerald-500/40', 'from-emerald-500 to-green-600'],
                        'Books' => ['from-violet-500/20 to-purple-600/20', 'hover:border-violet-500/40', 'from-violet-500 to-purple-600'],
                        'Beauty' => ['from-rose-500/20 to-pink-600/20', 'hover:border-rose-500/40', 'from-rose-500 to-pink-600'],
                    ];
                @endphp
                @foreach($categories as $category)
                    @php $colors = $categoryColors[$category->name] ?? ['from-gray-500/20 to-slate-600/20', 'hover:border-gray-500/40', 'from-gray-500 to-slate-600']; @endphp
                    <a href="{{ route('categories.show', $category) }}"
                        class="group glass-card rounded-2xl p-6 text-center transition-all duration-500 border border-white/5 {{ $colors[1] }} animate-slide-up">
                        <div
                            class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br {{ $colors[0] }} flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 animate-glow-ring overflow-hidden">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span
                                    class="text-2xl font-black bg-gradient-to-br {{ $colors[2] }} bg-clip-text text-transparent">{{ substr($category->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-200 group-hover:text-white transition-colors">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $category->active_products_count }} items</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Neon Divider -->
    <div class="neon-line w-3/4 mx-auto"></div>

    <!-- Featured Products -->
    @if($featuredProducts->count())
        <section class="py-20 relative">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex items-end justify-between mb-12">
                    <div>
                        <span
                            class="inline-block px-4 py-1 bg-yellow-500/10 rounded-full text-yellow-400 text-sm font-semibold border border-yellow-500/20 mb-4">⭐
                            Handpicked</span>
                        <h2 class="text-3xl md:text-4xl font-black gradient-text">Featured Products</h2>
                        <p class="text-gray-400 mt-2">Our most popular picks, curated just for you</p>
                    </div>
                    <a href="{{ route('products.index') }}?featured=1"
                        class="hidden md:flex items-center gap-2 text-brand-400 hover:text-brand-300 font-semibold transition-colors group">
                        View All <span class="group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($featuredProducts as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Sale Banner Section -->
    @if($saleProducts->count())
        <section class="py-16 gradient-section relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 right-0 w-72 h-72 bg-red-500/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-orange-500/5 rounded-full blur-3xl"></div>
            </div>
            <div class="max-w-7xl mx-auto px-4 relative z-10">
                <div class="text-center mb-10">
                    <span
                        class="inline-block px-4 py-1 bg-red-500/15 rounded-full text-red-400 text-sm font-semibold border border-red-500/20 mb-4 animate-glow-ring">🔥
                        Limited Time</span>
                    <h2 class="text-3xl md:text-4xl font-black gradient-text-fire">Hot Deals & Offers</h2>
                    <p class="text-gray-400 mt-2">Don't miss these incredible savings</p>
                </div>

                <!-- Countdown Timer -->
                <div class="flex justify-center gap-4 mb-10" x-data="countdown()" x-init="start()">
                    <div class="countdown-box rounded-2xl px-5 py-3 text-center min-w-[80px]">
                        <div class="text-2xl md:text-3xl font-black text-white" x-text="hours">00</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wider">Hours</div>
                    </div>
                    <div class="text-2xl text-gray-600 font-bold self-center">:</div>
                    <div class="countdown-box rounded-2xl px-5 py-3 text-center min-w-[80px]">
                        <div class="text-2xl md:text-3xl font-black text-white" x-text="minutes">00</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wider">Minutes</div>
                    </div>
                    <div class="text-2xl text-gray-600 font-bold self-center">:</div>
                    <div class="countdown-box rounded-2xl px-5 py-3 text-center min-w-[80px]">
                        <div class="text-2xl md:text-3xl font-black text-white" x-text="seconds">00</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wider">Seconds</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($saleProducts as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Latest Products -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <span
                        class="inline-block px-4 py-1 bg-neon-cyan/10 rounded-full text-neon-cyan text-sm font-semibold border border-neon-cyan/20 mb-4">🆕
                        Just In</span>
                    <h2 class="text-3xl md:text-4xl font-black gradient-text-emerald">Latest Products</h2>
                    <p class="text-gray-400 mt-2">Freshly added to our collection</p>
                </div>
                <a href="{{ route('products.index') }}?sort=newest"
                    class="hidden md:flex items-center gap-2 text-neon-cyan hover:text-cyan-300 font-semibold transition-colors group">
                    View All <span class="group-hover:translate-x-1 transition-transform">→</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($latestProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>

    <!-- Animated Brands Marquee -->
    <section class="py-12 border-y border-white/5 overflow-hidden">
        <div class="text-center mb-8">
            <span class="text-sm text-gray-500 font-medium uppercase tracking-widest">Trusted by Leading Brands</span>
        </div>
        <div class="relative">
            <div class="flex animate-marquee whitespace-nowrap">
                @php
                    $brands = ['Apple', 'Samsung', 'Nike', 'Adidas', 'Sony', 'LG', 'Puma', 'Levi\'s', 'Canon', 'Dyson', 'Bose', 'Under Armour', 'Apple', 'Samsung', 'Nike', 'Adidas', 'Sony', 'LG', 'Puma', 'Levi\'s', 'Canon', 'Dyson', 'Bose', 'Under Armour'];
                @endphp
                @foreach($brands as $brand)
                    <div
                        class="flex items-center gap-3 mx-10 text-gray-600 hover:text-gray-300 transition-colors cursor-default group">
                        <div
                            class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-lg font-black group-hover:bg-white/10 transition-colors">
                            {{ substr($brand, 0, 1) }}
                        </div>
                        <span class="text-lg font-bold tracking-wide">{{ $brand }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-20 relative">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-14">
                <span
                    class="inline-block px-4 py-1 bg-emerald-500/10 rounded-full text-emerald-400 text-sm font-semibold border border-emerald-500/20 mb-4">Why
                    Huzaifa Store?</span>
                <h2 class="text-3xl md:text-4xl font-black gradient-text-emerald">Why Customers Love Us</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $features = [
                        ['icon' => '🚀', 'title' => 'Fast Delivery', 'desc' => 'Free shipping on orders over $100. Same-day delivery available in select areas.', 'gradient' => 'from-blue-500/10 to-indigo-500/10'],
                        ['icon' => '🛡️', 'title' => 'Secure Payments', 'desc' => '256-bit SSL encryption protects every transaction. Your data is always safe.', 'gradient' => 'from-emerald-500/10 to-green-500/10'],
                        ['icon' => '💎', 'title' => 'Premium Quality', 'desc' => 'Handpicked products from trusted brands. Quality guaranteed or your money back.', 'gradient' => 'from-purple-500/10 to-violet-500/10'],
                        ['icon' => '🎧', 'title' => '24/7 Support', 'desc' => 'Round-the-clock customer service via chat, email, and phone. We\'re always here.', 'gradient' => 'from-amber-500/10 to-orange-500/10'],
                    ];
                @endphp
                @foreach($features as $i => $feature)
                    <div class="glass-card rounded-2xl p-8 group hover-rotate-3d">
                        <div
                            class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $feature['gradient'] }} flex items-center justify-center text-3xl mb-5 group-hover:scale-110 transition-transform duration-500">
                            {{ $feature['icon'] }}
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-20 gradient-section relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-10 right-20 w-64 h-64 bg-brand-500/10 rounded-full blur-3xl animate-float-slow"></div>
            <div class="absolute bottom-10 left-20 w-48 h-48 bg-neon-cyan/10 rounded-full blur-3xl animate-float"
                style="animation-delay: -3s;"></div>
        </div>
        <div class="max-w-3xl mx-auto px-4 text-center relative z-10">
            <div class="text-5xl mb-6 animate-float">📧</div>
            <h2 class="text-3xl md:text-4xl font-black gradient-text-shimmer mb-4">Stay in the Loop</h2>
            <p class="text-gray-400 mb-8 max-w-lg mx-auto">Subscribe to our newsletter and get exclusive deals, early access
                to new products, and 10% off your first order.</p>
            <form class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto"
                onsubmit="event.preventDefault(); alert('Thanks for subscribing!');">
                <input type="email" placeholder="Enter your email address" required
                    class="flex-1 px-6 py-4 rounded-xl bg-dark-800/80 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent backdrop-blur-sm">
                <button type="submit"
                    class="px-8 py-4 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-bold shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transform hover:scale-105 transition-all duration-300 btn-glow whitespace-nowrap">
                    Subscribe ✨
                </button>
            </form>
            <p class="text-xs text-gray-600 mt-4">No spam, unsubscribe at any time. We respect your privacy.</p>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-14">
                <span
                    class="inline-block px-4 py-1 bg-neon-pink/10 rounded-full text-neon-pink text-sm font-semibold border border-neon-pink/20 mb-4">💬
                    Testimonials</span>
                <h2 class="text-3xl md:text-4xl font-black gradient-text-rose">What Our Customers Say</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @php
                    $testimonials = [
                        ['name' => 'Sarah Johnson', 'role' => 'Verified Buyer', 'text' => 'Absolutely love the quality of products! The delivery was incredibly fast and everything arrived in perfect condition. Will definitely shop here again!', 'rating' => 5, 'avatar' => '👩‍💼'],
                        ['name' => 'Michael Chen', 'role' => 'Loyal Customer', 'text' => 'Best online shopping experience I\'ve had in years. The UI is smooth, product selection is amazing, and their customer support is top-notch.', 'rating' => 5, 'avatar' => '👨‍💻'],
                        ['name' => 'Emily Rodriguez', 'role' => 'Fashion Enthusiast', 'text' => 'The fashion collection here is incredible. Found items I couldn\'t find anywhere else, and the prices are very competitive. Love the detailed product descriptions!', 'rating' => 4, 'avatar' => '👩‍🎨'],
                    ];
                @endphp
                @foreach($testimonials as $i => $testimonial)
                    <div class="glass-card rounded-2xl p-8 relative group hover-rotate-3d">
                        <div class="absolute top-4 right-4 text-6xl text-brand-500/10 font-serif">"</div>
                        <div class="flex items-center gap-1 mb-4">
                            @for($s = 1; $s <= 5; $s++)
                                <svg class="w-4 h-4 {{ $s <= $testimonial['rating'] ? 'text-yellow-400' : 'text-gray-600' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="text-gray-300 text-sm leading-relaxed mb-6 italic">"{{ $testimonial['text'] }}"</p>
                        <div class="flex items-center gap-3 border-t border-white/5 pt-4">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-500/20 to-neon-cyan/20 flex items-center justify-center text-xl">
                                {{ $testimonial['avatar'] }}</div>
                            <div>
                                <div class="font-bold text-gray-200 text-sm">{{ $testimonial['name'] }}</div>
                                <div class="text-xs text-gray-500">{{ $testimonial['role'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        function heroSlider() {
            return {
                current: 0,
                slides: @json($heroSlides),
                startAutoSlide() { setInterval(() => { this.current = (this.current + 1) % this.slides.length; }, 5000); },
                goTo(index) { this.current = index; },
                tilt3D(e, el) {
                    const rect = el.getBoundingClientRect();
                    const x = ((e.clientX - rect.left) / rect.width - 0.5) * 20;
                    const y = ((e.clientY - rect.top) / rect.height - 0.5) * -20;
                    el.style.transform = `perspective(1000px) rotateX(${y}deg) rotateY(${x}deg) scale(1.03)`;
                },
                resetTilt(el) { el.style.transform = ''; }
            };
        }

        function countdown() {
            return {
                hours: '00', minutes: '00', seconds: '00',
                start() {
                    const update = () => {
                        const now = new Date();
                        const end = new Date(now);
                        end.setHours(23, 59, 59, 999);
                        const diff = end - now;
                        this.hours = String(Math.floor(diff / 3600000)).padStart(2, '0');
                        this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
                        this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
                    };
                    update();
                    setInterval(update, 1000);
                }
            };
        }
    </script>
@endsection
