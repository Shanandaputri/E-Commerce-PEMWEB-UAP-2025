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

    {{-- HEADER --}}
    @includeIf('partials.header')

    @php
        $avgRating    = round($product->productReviews->avg('rating') ?? 4.5, 1);
        $reviewsCount = $product->productReviews->count();
    @endphp

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

                    <hr class="my-2">

                    {{-- Select Color --}}
                    <div class="space-y-3">
                        <p class="text-sm font-medium text-gray-900">Select Colors</p>
                        <div class="flex gap-3">
                            <button class="w-10 h-10 rounded-full bg-[#4F5665] border-2 border-black ring-2 ring-offset-2 ring-black"></button>
                            <button class="w-10 h-10 rounded-full bg-[#314F4A] hover:border-2 hover:border-gray-400 transition"></button>
                            <button class="w-10 h-10 rounded-full bg-[#31344F] hover:border-2 hover:border-gray-400 transition"></button>
                        </div>
                    </div>

                    <hr class="my-2">

                    {{-- Choose Size --}}
                    <div class="space-y-3">
                        <p class="text-sm font-medium text-gray-900">Choose Size</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach(['S','M','L','XL'] as $size)
                                <button
                                    class="px-6 py-2.5 text-sm font-medium rounded-full bg-gray-100 hover:bg-black hover:text-white transition border border-transparent"
                                >
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-2">

                    {{-- Qty + Add to Cart --}}
                    <div class="flex items-center gap-4 mt-2">
                        <div class="flex items-center bg-gray-100 rounded-full px-5 py-3">
                            <button class="px-3 text-lg font-medium hover:opacity-70">−</button>
                            <span class="px-4 text-base font-medium min-w-[2rem] text-center">1</span>
                            <button class="px-3 text-lg font-medium hover:opacity-70">+</button>
                        </div>

                        <button
                            class="flex-1 bg-black text-white py-3.5 rounded-full text-base font-semibold hover:bg-gray-800 transition"
                        >
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== RATING & REVIEWS ===================== --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">

            {{-- Title tengah + garis bawah (kayak di Figma) --}}
            <div class="mb-8">
                <h2 class="text-center text-lg md:text-xl font-semibold">
                    Rating & Reviews
                </h2>
                <div class="mt-3 border-b border-gray-200"></div>
            </div>

            {{-- Header All Reviews + filter --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl md:text-2xl font-bold">
                        All Reviews
                        @if($reviewsCount > 0)
                            <span class="text-gray-400">({{ $reviewsCount }})</span>
                        @else
                            {{-- fallback biar mirip Figma kalau belum ada data --}}
                            <span class="text-gray-400">(451)</span>
                        @endif
                    </h3>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium bg-gray-100 rounded-full hover:bg-gray-200 transition"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                        </svg>
                        Latest
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <button
                        class="px-5 py-2.5 text-sm font-semibold bg-black text-white rounded-full hover:bg-gray-800 transition"
                    >
                        Write a Review
                    </button>
                </div>
            </div>

            {{-- Review cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @forelse($product->productReviews as $review)
                    <article class="border border-gray-200 rounded-3xl p-6">
                        {{-- Bintang --}}
                        <div class="flex text-yellow-400 mb-3">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 fill-current {{ $i < ($review->rating ?? 5) ? '' : 'opacity-30' }}" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @endfor
                        </div>

                        {{-- Nama & menu --}}
                        <div class="flex items-start justify-between mb-3">
                            <p class="font-semibold text-base">
                                {{ $review->user->name ?? 'Anonymous' }}
                                <span class="text-green-500 ml-1">✓</span>
                            </p>
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="5" cy="12" r="2"></circle>
                                    <circle cx="12" cy="12" r="2"></circle>
                                    <circle cx="19" cy="12" r="2"></circle>
                                </svg>
                            </button>
                        </div>

                        {{-- Isi review --}}
                        <p class="text-sm text-gray-600 leading-relaxed mb-4">
                            "{{ $review->comment ?? 'Great product! Highly recommended.' }}"
                        </p>

                        {{-- Tanggal --}}
                        <p class="text-xs text-gray-400">
                            Posted on {{ optional($review->created_at)->format('F d, Y') }}
                        </p>
                    </article>
                @empty
                    {{-- Dummy reviews kalau belum ada review di DB (biar tetap mirip Figma) --}}
                    @foreach ([
                        ['name' => 'Samantha D.', 'date' => 'August 14, 2023', 'rating' => 5, 'comment' => "I absolutely love this t-shirt! The design is unique and the fabric feels so comfortable. As a fellow designer, I appreciate the attention to detail. It's become my favorite go-to shirt."],
                        ['name' => 'Alex M.', 'date' => 'August 15, 2023', 'rating' => 5, 'comment' => "The t-shirt exceeded my expectations! The colors are vibrant and the print quality is top-notch. Being a UI/UX designer myself, I'm quite picky about aesthetics, and this t-shirt definitely gets a thumbs up from me."],
                        ['name' => 'Ethan R.', 'date' => 'August 16, 2023', 'rating' => 4, 'comment' => "This t-shirt is a must-have for anyone who appreciates good design. The minimalistic yet stylish pattern caught my eye, and the fit is perfect. I can see the designer's touch in every aspect of this shirt."],
                        ['name' => 'Olivia P.', 'date' => 'August 17, 2023', 'rating' => 4, 'comment' => "As a UI/UX enthusiast, I value simplicity and functionality. This t-shirt not only represents those principles but also feels great to wear. It's evident that the designer poured their creativity into making this t-shirt stand out."],
                    ] as $fake)
                        <article class="border border-gray-200 rounded-3xl p-6">
                            <div class="flex text-yellow-400 mb-3">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-5 h-5 fill-current {{ $i < $fake['rating'] ? '' : 'opacity-30' }}" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endfor
                            </div>

                            <div class="flex items-start justify-between mb-3">
                                <p class="font-semibold text-base">
                                    {{ $fake['name'] }} <span class="text-green-500 ml-1">✓</span>
                                </p>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="5" cy="12" r="2"></circle>
                                        <circle cx="12" cy="12" r="2"></circle>
                                        <circle cx="19" cy="12" r="2"></circle>
                                    </svg>
                                </button>
                            </div>

                            <p class="text-sm text-gray-600 leading-relaxed mb-4">
                                "{{ $fake['comment'] }}"
                            </p>

                            <p class="text-xs text-gray-400">
                                Posted on {{ $fake['date'] }}
                            </p>
                        </article>
                    @endforeach
                @endforelse
            </div>

            <div class="flex justify-center mt-8">
                <button class="px-8 py-3 text-sm font-medium border border-gray-300 rounded-full hover:bg-gray-50 transition">
                    Load More Reviews
                </button>
            </div>
        </section>
    </main>

{{-- NEWSLETTER SECTION --}}
<section class="py-16 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="bg-black rounded-[2.5rem] px-8 py-12 md:px-16 md:py-14 text-center text-white">
            <h2 class="text-3xl md:text-[2.5rem] font-bold font-integral mb-8 leading-tight">
                STAY UPTO DATE ABOUT<br>OUR LATEST OFFERS
            </h2>
            <div class="max-w-md mx-auto space-y-4">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <input 
                        type="email" 
                        placeholder="Enter your email address" 
                        class="w-full pl-12 pr-6 py-3.5 rounded-full text-black text-sm focus:outline-none focus:ring-2 focus:ring-gray-300"
                    >
                </div>
                <button class="w-full bg-white text-black px-6 py-3.5 rounded-full font-semibold text-sm hover:bg-gray-100 transition">
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
                <h3 class="text-3xl font-bold font-integral mb-4">SHOP.CO</h3>
                <p class="text-gray-600 text-sm mb-6 max-w-xs">
                    We have clothes that suits your style and which you're proud to wear. From women to men.
                </p>
                <div class="flex space-x-3">
                    <a href="#" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>
                    </a>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4 text-base">COMPANY</h4>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-gray-900 transition">About</a></li>
                    <li><a href="#" class="hover:text-gray-900 transition">Features</a></li>
                    <li><a href="#" class="hover:text-gray-900 transition">Works</a></li>
                    <li><a href="#" class="hover:text-gray-900 transition">Career</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4 text-base">HELP</h4>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-gray-900 transition">Customer Support</a></li>
                    <li><a href="#" class="hover:text-gray-900 transition">Delivery Details</a></li>
                    <li><a href="#" class="hover:text-gray-900 transition">Terms & Conditions</a></li>
                    <li><a href="#" class="hover:text-gray-900 transition">Privacy Policy</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4 text-base">RESOURCES</h4>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-gray-900 transition">Free eBooks</a></li>
                    <li><a href="#" class="hover:text-gray-900 transition">Development Tutorial</a></li>
                    <li><a href="#" class="hover:text-gray-900 transition">How to - Blog</a></li>
                    <li><a href="#" class="hover:text-gray-900 transition">Youtube Playlist</a></li>
                </ul>
            </div>
        </div>
        
        <hr class="mb-8 border-gray-200">
        
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
<script>
    // Ganti gambar utama saat thumbnail diklik
    document.querySelectorAll('.thumb-btn').forEach(button => {
        button.addEventListener('click', () => {
            const newImageSrc = button.getAttribute('data-image');
            document.getElementById('main-product-image').src = newImageSrc;
        });
    });
</script>
</body>
</html>