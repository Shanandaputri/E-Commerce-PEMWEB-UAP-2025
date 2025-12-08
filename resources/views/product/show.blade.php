<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - {{ config('app.name', 'SHOP.CO') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=integral-cf:400,700|satoshi:400,500,700|anton:400&display=swap"
        rel="stylesheet"
    />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Satoshi', system-ui, -apple-system, BlinkMacSystemFont, sans-serif; }
        .font-integral { font-family: 'Integral CF', sans-serif; }
        .font-anton   { font-family: 'Anton', sans-serif; }
    </style>
</head>
<body class="bg-white">

    {{-- HEADER & NAVIGATION --}}
    @includeIf('partials.header')

    @php
        $avgRating    = round($product->productReviews->avg('rating') ?? 4.5, 1);
        $reviewsCount = $product->productReviews->count();
    @endphp

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

    {{-- ===================== PRODUCT DETAIL SECTION ========================= --}}
    <main class="bg-white pt-6 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Breadcrumb --}}
            <div class="text-sm text-gray-500 mb-6">
                <a href="{{ route('home') }}" class="hover:underline">Home</a>
                <span class="mx-2">&gt;</span>
                <span>{{ $product->productCategory->name ?? 'Category' }}</span>
                <span class="mx-2">&gt;</span>
                <span class="text-gray-900">{{ $product->name }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

                {{-- LEFT: GALLERY --}}
                <div class="flex gap-4">

                    {{-- Thumbnails (Vertical) --}}
                    <div class="flex flex-col gap-3 w-28">
                        @foreach($product->productImages as $image)
                        <button
                            type="button"
                            class="border-2 border-gray-200 rounded-2xl overflow-hidden hover:border-gray-400 transition thumb-btn focus:border-black"
                            data-image="{{ asset($image->image) }}"
                        >
                            <img
                                src="{{ asset($image->image) }}"
                                alt="{{ $product->name }}"
                                class="w-full h-28 object-cover"
                            >
                        </button>
                        @endforeach
                    </div>

                    {{-- Main Image --}}
                    <div class="flex-1">
                        @php $mainImage = $product->productImages->first(); @endphp

                        <div class="aspect-[3/4] bg-gray-50 rounded-3xl overflow-hidden">
                            @if($mainImage)
                                <img 
                                    id="main-product-image"
                                    src="{{ asset($mainImage->image) }}" 
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    No Image
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- RIGHT: INFO --}}
                <div class="flex flex-col gap-5">
                    
                    {{-- Sold by (Store name) --}}
                    @if($product->store)
                        <p class="text-xs text-gray-500 uppercase tracking-wider">
                            SOLD BY <span class="font-semibold text-gray-800">{{ $product->store->name }}</span>
                        </p>
                    @endif

                    {{-- Product title --}}
                    <h1 class="font-anton text-4xl tracking-tight uppercase leading-tight">
                        {{ $product->name }}
                    </h1>

                    {{-- Rating + review count --}}
                    <div class="flex items-center gap-2">
                        <div class="flex text-yellow-400">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm font-medium">{{ $avgRating }}/5</span>
                    </div>

                    {{-- Price --}}
                    <div class="text-3xl font-bold">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>

                    {{-- Short description --}}
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $product->small_description
                            ?? $product->description
                            ?? 'This product is perfect for any occasion. Crafted from premium materials.' }}
                    </p>


        <form method="POST" action="{{ route('cart.add', $product->id) }}" id="add-to-cart-form">
            @csrf

            {{-- hidden input utk value yang dipilih --}}
            <input type="hidden" name="color" id="colorInput">
            <input type="hidden" name="size" id="sizeInput">
            <input type="hidden" name="qty" id="qtyInput" value="1">

            {{-- Select Color --}}
            <div class="space-y-3 mt-2">
                <p class="text-sm font-medium text-gray-900">Select Colors</p>
                <div class="flex gap-3" id="colorOptions">
                    <button type="button"
                            class="color-option w-10 h-10 rounded-full bg-[#4F5665] border-2 border-transparent hover:border-black transition"
                            data-color="Dark Gray">
                    </button>
                    <button type="button"
                            class="color-option w-10 h-10 rounded-full bg-[#314F4A] border-2 border-transparent hover:border-black transition"
                            data-color="Green">
                    </button>
                    <button type="button"
                            class="color-option w-10 h-10 rounded-full bg-[#31344F] border-2 border-transparent hover:border-black transition"
                            data-color="Navy">
                    </button>
                </div>
                <p class="text-xs text-red-500 hidden" id="colorError">Pilih warna dulu.</p>
            </div>

            <hr class="my-2">
            <br>

            {{-- Choose Size --}}
            <div class="space-y-3">
                <p class="text-sm font-medium text-gray-900">Choose Size</p>
                <div class="flex flex-wrap gap-3" id="sizeOptions">
                    @foreach(['S','M','L','XL'] as $size)
                        <button
                            type="button"
                            class="size-option px-6 py-2.5 text-sm font-medium rounded-full bg-gray-100 hover:bg-black hover:text-white transition border border-transparent"
                            data-size="{{ $size }}"
                        >
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
                <p class="text-xs text-red-500 hidden" id="sizeError">Pilih ukuran dulu.</p>
            </div>

            <hr class="my-2">
            <br>
            

            {{-- Qty + Add to Cart --}}
            <div class="flex items-center gap-4 mt-2">
                <div class="flex items-center bg-gray-100 rounded-full px-5 py-3">
                    <button type="button" id="qtyMinus" class="px-3 text-lg font-medium hover:opacity-70">−</button>
                    <span id="qtyDisplay" class="px-4 text-base font-medium min-w-[2rem] text-center">1</span>
                    <button type="button" id="qtyPlus" class="px-3 text-lg font-medium hover:opacity-70">+</button>
                </div>

                <button
                    type="submit"
                    class="flex-1 bg-black text-white py-3.5 rounded-full text-base font-semibold hover:bg-gray-800 transition"
                >
                    Add to Cart
                </button>
            </div>
        </form>

<script>
    // Ganti gambar utama saat thumbnail diklik
    document.querySelectorAll('.thumb-btn').forEach(button => {
        button.addEventListener('click', () => {
            const newImageSrc = button.getAttribute('data-image');
            document.getElementById('main-product-image').src = newImageSrc;
        });
    });

    // ====== PILIH WARNA ======
    const colorButtons = document.querySelectorAll('.color-option');
    const colorInput   = document.getElementById('colorInput');
    const colorError   = document.getElementById('colorError');

    colorButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // reset style
            colorButtons.forEach(b => b.classList.remove('ring-2', 'ring-black', 'border-black'));
            // set aktif
            btn.classList.add('ring-2', 'ring-black', 'border-black');

            colorInput.value = btn.dataset.color;
            colorError.classList.add('hidden');
        });
    });

    // ====== PILIH SIZE ======
    const sizeButtons = document.querySelectorAll('.size-option');
    const sizeInput   = document.getElementById('sizeInput');
    const sizeError   = document.getElementById('sizeError');

    sizeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            sizeButtons.forEach(b => b.classList.remove('bg-black', 'text-white'));
            sizeButtons.forEach(b => b.classList.add('bg-gray-100', 'text-black'));

            btn.classList.remove('bg-gray-100');
            btn.classList.add('bg-black', 'text-white');

            sizeInput.value = btn.dataset.size;
            sizeError.classList.add('hidden');
        });
    });

    // ====== QTY PLUS / MINUS ======
    const qtyInput   = document.getElementById('qtyInput');
    const qtyDisplay = document.getElementById('qtyDisplay');
    const btnPlus    = document.getElementById('qtyPlus');
    const btnMinus   = document.getElementById('qtyMinus');

    btnPlus.addEventListener('click', () => {
        let val = parseInt(qtyInput.value || '1', 10);
        val++;
        qtyInput.value   = val;
        qtyDisplay.textContent = val;
    });

    btnMinus.addEventListener('click', () => {
        let val = parseInt(qtyInput.value || '1', 10);
        if (val > 1) val--;
        qtyInput.value   = val;
        qtyDisplay.textContent = val;
    });

    // ====== VALIDASI SEBELUM SUBMIT ======
    const form = document.getElementById('add-to-cart-form');
    form.addEventListener('submit', function (e) {
        let ok = true;

        if (!colorInput.value) {
            colorError.classList.remove('hidden');
            ok = false;
        }
        if (!sizeInput.value) {
            sizeError.classList.remove('hidden');
            ok = false;
        }

        if (!ok) {
            e.preventDefault(); // stop submit
        }
    });
</script>
</body>
</html>