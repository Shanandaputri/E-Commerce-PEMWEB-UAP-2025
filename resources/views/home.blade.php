<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'SHOP.CO') }}</title>
       
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=integral-cf:400,700|anton:400|poppins:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            }

            .font-integral {
                font-family: 'Integral CF', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            }

            .font-anton {
                font-family: 'Anton', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
                letter-spacing: 0.03em;
            }
        </style>
        
    </head>
    <body class="bg-white font-poppins">
        {{-- HEADER / NAVIGATION --}}
<header class="border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            {{-- Logo --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold font-integral">
                    SHOP.CO
                </a>
                
                {{-- Navigation Menu --}}
                <nav class="hidden md:ml-10 md:flex md:space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-900 hover:text-gray-600">Home</a>
                    
                    {{-- Dropdown Kategori --}}
                    <div class="relative group">
                        <button class="text-gray-900 hover:text-gray-600 inline-flex items-center">
                            Kategori
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-1">
                                @foreach($categories as $category)
                                    <a href="?category={{ $category->id }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    

                </nav>
            </div>
            
            {{-- Search Bar --}}
            <div class="hidden md:flex flex-1 max-w-md mx-8">
                <div class="w-full">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search for products..." 
                               class="w-full bg-gray-50 border-0 rounded-full py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Right Icons --}}
            <div class="flex items-center space-x-4">
                {{-- Cart Icon --}}
                <button class="p-2 hover:bg-gray-100 rounded-full">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </button>
                
                {{-- Profile Dropdown --}}
                @auth
                    <div class="relative group">
                        <button class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded-full">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </button>
                        
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-1">
                                <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                    <p class="font-semibold">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                                
                                {{-- E-Wallet/Saldo --}}
                                <a href="{{ route('wallet.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <div class="flex items-center justify-between">
                                        <span>E-Wallet</span>
                                        <span class="font-semibold text-green-600">
                                            Rp {{ number_format(auth()->user()->wallet->balance ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </a>

                                
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profile
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Login</a>
                    <a href="{{ route('register') }}" class="text-sm bg-black text-white px-4 py-2 rounded-full hover:bg-gray-800">Register</a>
                @endauth
            </div>
        </div>
    </div>
</header>

{{-- HERO SECTION --}}
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-8">
        <div
            class="relative rounded-[40px] overflow-hidden min-h-[420px] lg:min-h-[480px] flex items-center"
            style="
                background-image: url('{{ asset('images/hero/hero1.jpg') }}');
                background-size: cover;
                background-position: right center;
                background-repeat: no-repeat;
                background-color: #f5f1f1;
            "
        >
            <div class="max-w-xl ml-6 md:ml-12 space-y-6">
                <h1 class="font-anton text-4xl md:text-6xl font-bold leading-tight">
                    FIND CLOTHES<br>
                    THAT MATCHES<br>
                    YOUR STYLE
                </h1>

                <p class="text-gray-700 text-sm md:text-base">
                    Browse through our diverse range of meticulously crafted garments,
                    designed to bring out your individuality and cater to your sense of style.
                </p>

                <a href="#new-arrivals"
                   class="inline-block bg-black text-white px-12 py-3 rounded-full hover:bg-gray-800 transition">
                    Shop Now
                </a>

                <div class="mt-10 grid grid-cols-3 gap-6 md:gap-8">
                    <div>
                        <p class="text-2xl md:text-3xl font-bold">200+</p>
                        <p class="text-gray-700 text-xs md:text-sm">International Brands</p>
                    </div>
                    <div>
                        <p class="text-2xl md:text-3xl font-bold">2,000+</p>
                        <p class="text-gray-700 text-xs md:text-sm">High-Quality Products</p>
                    </div>
                    <div>
                        <p class="text-2xl md:text-3xl font-bold">{{ number_format($allProducts->count()) }}+</p>
                        <p class="text-gray-700 text-xs md:text-sm">Happy Customers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BRAND LOGOS --}}
    <div class="bg-black py-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12 lg:gap-16">
                
                <img src="{{ asset('images/brands/versace.png') }}"
                     alt="Versace"
                     class="h-6 md:h-8 lg:h-10 opacity-90 hover:opacity-100 transition object-contain">

                <img src="{{ asset('images/brands/zara.png') }}"
                     alt="Zara"
                     class="h-6 md:h-8 lg:h-10 opacity-90 hover:opacity-100 transition object-contain">

                <img src="{{ asset('images/brands/gucci.png') }}"
                     alt="Gucci"
                     class="h-6 md:h-8 lg:h-10 opacity-90 hover:opacity-100 transition object-contain">

                <img src="{{ asset('images/brands/prada.png') }}"
                     alt="Prada"
                     class="h-6 md:h-8 lg:h-10 opacity-90 hover:opacity-100 transition object-contain">

                <img src="{{ asset('images/brands/calvin.png') }}"
                     alt="Calvin Klein"
                     class="h-6 md:h-8 lg:h-10 opacity-90 hover:opacity-100 transition object-contain">
                
            </div>
        </div>
    </div>
</section>

{{-- NEW ARRIVALS SECTION --}}
<section class="py-16" id="new-arrivals">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold font-anton text-center mb-12">
            NEW ARRIVALS
        </h2>
        
        {{-- Grid Products --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6" id="new-arrivals-grid">
            {{-- Tampilkan 4 produk pertama --}}
            @foreach($newArrivals as $product)
            <a href="{{ route('product.show', $product->slug) }}" class="group cursor-pointer block">
                <div class="aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden mb-3">
                    @php
                        $thumb = $product->productImages->first();
                    @endphp
                    @if($thumb)
                        <img src="{{ asset($thumb->image) }}" 
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover object-top group-hover:scale-110 transition duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            No Image
                        </div>
                    @endif
                </div>
                <h3 class="font-semibold mb-1">{{ $product->name }}</h3>
                <div class="flex items-center mb-1">
                    <div class="flex text-yellow-400 text-sm">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-sm text-gray-600 ml-2">4.5/5</span>
                </div>
                <p class="font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </a>
            @endforeach

            {{-- Hidden products (akan ditampilkan saat klik View All) --}}
            @foreach($allProducts->skip(4) as $product)
            <a href="{{ route('product.show', $product->slug) }}" class="group cursor-pointer block hidden new-arrivals-hidden">
                <div class="aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden mb-3">
                    @php
                        $thumb = $product->productImages->first();
                    @endphp
                    @if($thumb)
                        <img src="{{ asset($thumb->image) }}" 
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover object-top group-hover:scale-110 transition duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            No Image
                        </div>
                    @endif
                </div>
                <h3 class="font-semibold mb-1">{{ $product->name }}</h3>
                <div class="flex items-center mb-1">
                    <div class="flex text-yellow-400 text-sm">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-sm text-gray-600 ml-2">4.5/5</span>
                </div>
                <p class="font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </a>
            @endforeach
        </div>
        
        {{-- Button View All --}}
        <div class="text-center mt-8">
            <button onclick="toggleProducts('new-arrivals')" id="new-arrivals-btn" class="border-2 border-gray-200 px-12 py-3 rounded-full hover:bg-gray-50 transition">
                View All
            </button>
        </div>
    </div>
</section>

<hr class="max-w-7xl mx-auto">

{{-- TOP SELLING SECTION --}}
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold font-integral text-center mb-12">
            TOP SELLING
        </h2>
        
        {{-- Grid Products --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6" id="top-selling-grid">
            {{-- Tampilkan 4 produk pertama --}}
            @foreach($topSelling as $product)
                <a href="{{ route('product.show', $product->slug) }}" class="group cursor-pointer block">
                    <div class="aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden mb-3">
                        @php
                            $thumb = $product->productImages->first();
                        @endphp
                        @if($thumb)
                            <img src="{{ asset($thumb->image) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover object-top group-hover:scale-110 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        @endif
                    </div>
                    <h3 class="font-semibold mb-1">{{ $product->name }}</h3>
                    <div class="flex items-center mb-1">
                        <div class="flex text-yellow-400 text-sm">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600 ml-2">4.5/5</span>
                    </div>
                    <p class="font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </a>
            @endforeach

            {{-- Hidden products (akan ditampilkan saat klik View All) --}}
            @foreach($allProducts->skip(8)->take(20) as $product)
                <a href="{{ route('product.show', $product->slug) }}" class="group cursor-pointer block hidden top-selling-hidden">
                    <div class="aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden mb-3">
                        @php
                            $thumb = $product->productImages->first();
                        @endphp
                        @if($thumb)
                            <img src="{{ asset($thumb->image) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover object-top group-hover:scale-110 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        @endif
                    </div>
                    <h3 class="font-semibold mb-1">{{ $product->name }}</h3>
                    <div class="flex items-center mb-1">
                        <div class="flex text-yellow-400 text-sm">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600 ml-2">4.5/5</span>
                    </div>
                    <p class="font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </a>
            @endforeach
        </div>
        
        {{-- Button View All --}}
        <div class="text-center mt-8">
            <button onclick="toggleProducts('top-selling')" id="top-selling-btn" class="border-2 border-gray-200 px-12 py-3 rounded-full hover:bg-gray-50 transition">
                View All
            </button>
        </div>
    </div>
</section>


{{-- JAVASCRIPT - LETAKKAN SEBELUM </body> --}}
<script>
    function toggleProducts(section) {
        const hiddenProducts = document.querySelectorAll(`.${section}-hidden`);
        const button = document.getElementById(`${section}-btn`);
        
        hiddenProducts.forEach(product => {
            if (product.classList.contains('hidden')) {
                // Show products dengan animasi
                product.classList.remove('hidden');
                product.style.animation = 'fadeIn 0.5s ease-in';
            } else {
                // Hide products
                product.classList.add('hidden');
            }
        });
        
        // Toggle button text
        if (button.textContent.trim() === 'View All') {
            button.textContent = 'Show Less';
        } else {
            button.textContent = 'View All';
        }
    }
</script>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<hr class="max-w-7xl mx-auto">

{{-- MODAL POPUP UNTUK VIEW ALL --}}
<div id="products-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-6xl w-full max-h-[90vh] overflow-hidden">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex items-center justify-between">
            <h3 id="modal-title" class="text-2xl font-bold font-anton">ALL PRODUCTS</h3>
            <button onclick="closeModal()" class="p-2 hover:bg-gray-100 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
            <div id="modal-products-grid" class="grid grid-cols-2 md:grid-cols-4 gap-6">
                {{-- Products akan dimuat via JavaScript --}}
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT JAVASCRIPT --}}
<script>
    // Data semua produk dari Laravel
    const allProducts = @json($allProducts);
    
    function showAllProducts(type) {
        const modal = document.getElementById('products-modal');
        const modalTitle = document.getElementById('modal-title');
        const grid = document.getElementById('modal-products-grid');
        
        // Update title
        if (type === 'new-arrivals') {
            modalTitle.textContent = 'NEW ARRIVALS - ALL PRODUCTS';
        } else if (type === 'top-selling') {
            modalTitle.textContent = 'TOP SELLING - ALL PRODUCTS';
        }
        
        // Clear grid
        grid.innerHTML = '';
        
        // Render all products
        allProducts.forEach(product => {
            const thumbnail = product.product_images && product.product_images.length > 0 
                ? product.product_images[0].image 
                : null;
            
            const productCard = `
                <a href="/product/${product.slug}" class="group cursor-pointer block">
                    <div class="aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden mb-3">
                        ${thumbnail 
                            ? `<img src="/${thumbnail}" alt="${product.name}" class="w-full h-full object-cover object-top group-hover:scale-110 transition duration-300">`
                            : `<div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>`
                        }
                    </div>
                    <h3 class="font-semibold mb-1 text-sm">${product.name}</h3>
                    <div class="flex items-center mb-1">
                        <div class="flex text-yellow-400 text-xs">
                            ${'<svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>'.repeat(5)}
                        </div>
                        <span class="text-xs text-gray-600 ml-1">4.5/5</span>
                    </div>
                    <p class="font-bold">Rp ${new Intl.NumberFormat('id-ID').format(product.price)}</p>
                </a>
            `;
            
            grid.innerHTML += productCard;
        });
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        const modal = document.getElementById('products-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Close modal ketika klik di luar
    document.getElementById('products-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Close modal dengan tombol ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>

{{-- NEWSLETTER SECTION --}}
<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-black rounded-3xl p-12 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold font-integral mb-6">
                STAY UPTO DATE ABOUT<br>OUR LATEST OFFERS
            </h2>
            <div class="max-w-md mx-auto space-y-4">
                <input type="email" 
                       placeholder="Enter your email address" 
                       class="w-full px-6 py-3 rounded-full text-black focus:outline-none">
                <button class="w-full bg-white text-black px-6 py-3 rounded-full font-semibold hover:bg-gray-100 transition">
                    Subscribe to Newsletter
                </button>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-gray-50 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">
            <div class="col-span-2">
                <h3 class="text-2xl font-bold font-integral mb-4">SHOP.CO</h3>
                <p class="text-gray-600 text-sm mb-4">
                    We have clothes that suits your style and which you're proud to wear. From women to men.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-8 h-8 bg-white border rounded-full flex items-center justify-center hover:bg-gray-100">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-8 h-8 bg-white border rounded-full flex items-center justify-center hover:bg-gray-100">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                    <a href="#" class="w-8 h-8 bg-white border rounded-full flex items-center justify-center hover:bg-gray-100">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.61-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                    </a>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4">COMPANY</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-gray-900">About</a></li>
                    <li><a href="#" class="hover:text-gray-900">Features</a></li>
                    <li><a href="#" class="hover:text-gray-900">Works</a></li>
                    <li><a href="#" class="hover:text-gray-900">Career</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4">HELP</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-gray-900">Customer Support</a></li>
                    <li><a href="#" class="hover:text-gray-900">Delivery Details</a></li>
                    <li><a href="#" class="hover:text-gray-900">Terms & Conditions</a></li>
                    <li><a href="#" class="hover:text-gray-900">Privacy Policy</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4">RESOURCES</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-gray-900">Free eBooks</a></li>
                    <li><a href="#" class="hover:text-gray-900">Development Tutorial</a></li>
                    <li><a href="#" class="hover:text-gray-900">How to - Blog</a></li>
                    <li><a href="#" class="hover:text-gray-900">Youtube Playlist</a></li>
                </ul>
            </div>
        </div>
    <hr class="mb-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-600">
        <p>Shop.co © 2000-2023, All Rights Reserved</p>
        <div class="flex space-x-4 mt-4 md:mt-0">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-6">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-6">
            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" class="h-6">
            <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple Pay" class="h-6">
            <img src="https://upload.wikimedia.org/wikipedia/commons/f/f2/Google_Pay_Logo.svg" alt="Google Pay" class="h-6">
        </div>
    </div>
</div>
</footer>
</body>
</html>
