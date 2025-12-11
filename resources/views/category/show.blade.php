<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $category->name ?? 'Kategori' }} - {{ config('app.name', 'SHOP.CO') }}</title>

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
<body class="bg-white text-gray-900">

    {{-- HEADER & NAVBAR --}}
    <header class="border-b border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <div class="flex items-center">

                    {{-- Logo --}}
                    <a href="{{ route('customer.dashboard') }}" class="text-2xl font-bold font-integral">
                        SHOP.CO
                    </a>

                    {{-- Nav --}}
                    <nav class="hidden md:ml-10 md:flex md:space-x-8 text-sm">

                        {{-- Home --}}
                        <a href="{{ route('customer.dashboard') }}"
                           class="text-gray-900 hover:text-gray-600">
                            Home
                        </a>

                        {{-- Dropdown Kategori --}}
                        <div class="relative group">
                            <a href="{{ route('categories.index') }}"
                               class="text-gray-900 hover:text-gray-600 inline-flex items-center">
                                <span>Kategori</span>
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7" />
                                </svg>
                            </a>

                            <div
                                class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible
                                       group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-1">
                                    @foreach($categories as $cat)
                                        <a
                                            href="{{ route('category.show', $cat->id) }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100
                                                   {{ isset($category) && $category->id === $cat->id ? 'bg-black text-white' : '' }}"
                                        >
                                            {{ $cat->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- History --}}
                        @auth
                            <a href="{{ route('transactions.index') }}"
                               class="text-gray-900 hover:text-gray-600">
                                History
                            </a>
                        @endauth
                    </nav>
                </div>

                {{-- SEARCH BAR --}}
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <div class="w-full">
                        <div class="relative">
                            <input
                                id="product-search"
                                type="text"
                                placeholder="Search for products..."
                                class="w-full bg-gray-50 border-0 rounded-full py-2 pl-10 pr-4 text-sm 
                                    focus:outline-none focus:ring-2 focus:ring-black"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>

                            {{-- DROPDOWN HASIL SEARCH --}}
                            <div
                                id="search-results"
                                class="absolute mt-2 w-full bg-white rounded-2xl shadow-lg border border-gray-100
                                    max-h-80 overflow-y-auto z-50 hidden"
                            >
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cart & Profile --}}
                <div class="flex items-center gap-4">
                    {{-- Cart --}}
                    <button onclick="window.location.href='{{ route('cart.index') }}'"
                            class="p-2 hover:bg-gray-100 rounded-full">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </button>

                    {{-- Profile --}}
                    @auth
                        <div class="relative group">
                            <button class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded-full">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </button>

                            <div
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible
                                       group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-1">
                                    <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                        <p class="font-semibold">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                    </div>

                                    <a href="{{ route('wallet.index') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <div class="flex items-center justify-between">
                                            <span>E-Wallet</span>
                                            <span class="font-semibold text-green-600">
                                                Rp {{ number_format(auth()->user()->wallet->balance ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </a>

                                    <a href="{{ route('profile.edit') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Profile
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="text-sm bg-black text-white px-4 py-2 rounded-full hover:bg-gray-800">
                            Register
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="bg-gray-50 pt-6 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <div class="text-sm text-gray-500 mb-6">
                <a href="{{ route('customer.dashboard') }}" class="hover:underline">Home</a>
                <span class="mx-2">&gt;</span>
                <span>Kategori</span>
                @if(!empty($category))
                    <span class="mx-2">&gt;</span>
                    <span class="text-gray-900">{{ $category->name }}</span>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- SIDEBAR FILTER --}}
                <aside class="lg:col-span-1 bg-white rounded-3xl border border-gray-200 p-6 h-fit">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-base font-semibold">Filters</h2>
                        <button class="text-xs text-gray-500 hover:text-gray-800">Reset</button>
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-[0.15em] mb-3">
                            Kategori
                        </h3>

                        <ul class="space-y-1 text-sm">
                            @foreach($categories as $cat)
                                <li>
                                    <a
                                        href="{{ route('category.show', $cat->id) }}"
                                        class="flex items-center justify-between px-2 py-2 rounded-lg
                                            {{ (isset($category) && $category->id === $cat->id)
                                                ? 'bg-black text-white'
                                                : 'text-gray-700 hover:bg-gray-100' }}"
                                    >
                                        <span>{{ $cat->name }}</span>
                                        <span class="text-xs
                                            {{ (isset($category) && $category->id === $cat->id)
                                                ? 'text-gray-200'
                                                : 'text-gray-400' }}">
                                            {{ $cat->products_count ?? '' }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </aside>

                {{-- LIST PRODUK --}}
                <section class="lg:col-span-3">
                    {{-- Title kategori --}}
                    <header class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl md:text-3xl font-bold">
                            {{ $category->name ?? 'Produk' }}
                        </h1>

                        {{-- Sort dropdown --}}
                        <div class="hidden md:flex items-center gap-2 text-sm text-gray-600">
                            <span class="text-gray-500">Sort by:</span>
                            <button class="px-3 py-2 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center gap-1">
                                <span>Popular</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                    </header>

                    {{-- Grid produk --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">

                        @forelse($products as $product)
                            @php
                                $thumbnail = optional($product->productImages->first())->image;
                                $rating    = round($product->productReviews->avg('rating') ?? 4.5, 1);
                            @endphp

                            <article class="bg-white rounded-3xl border border-gray-200 overflow-hidden flex flex-col">
                                {{-- Image --}}
                                <a href="{{ route('product.show', $product->slug ?? $product->id) }}" class="block">
                                    <div class="aspect-[3/4] bg-gray-100">
                                        @if($thumbnail)
                                            <img
                                                src="{{ asset($thumbnail) }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover"
                                            >
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                                                No Image
                                            </div>
                                        @endif
                                    </div>
                                </a>

                                {{-- Content --}}
                                <div class="p-4 flex flex-col gap-1 flex-1">
                                    {{-- Rating --}}
                                    <div class="flex items-center gap-1 text-xs text-gray-600 mb-1">
                                        <div class="flex text-yellow-400">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg class="w-3.5 h-3.5 fill-current {{ $i < floor($rating) ? '' : 'opacity-30' }}"
                                                     viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span>{{ $rating }}/5</span>
                                    </div>

                                    {{-- Nama --}}
                                    <a href="{{ route('product.show', $product->slug ?? $product->id) }}"
                                       class="text-sm font-semibold uppercase tracking-tight line-clamp-2">
                                        {{ $product->name }}
                                    </a>

                                    {{-- Harga --}}
                                    <p class="mt-2 text-base font-bold">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </article>

                        @empty
                            <div class="col-span-full text-center py-12 text-gray-500">
                                Belum ada produk di kategori ini.
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8 flex justify-center">
                        {{ $products->links() }}
                    </div>
                </section>
            </div>
        </div>
    </main>

    {{-- SCRIPT SEARCH BAR --}}
    <script>
    (function () {
        const input     = document.getElementById('product-search');
        const box       = document.getElementById('search-results');
        const wrapper   = input ? input.closest('.relative') : null;
        const searchUrl = "{{ route('products.search') }}";
        const loginUrl  = "{{ route('login') }}";

        if (!input || !box) return;

        let timer = null;

        function clearResults() {
            box.innerHTML = '';
            box.classList.add('hidden');
        }

        function renderResults(items) {
            if (!items.length) {
                box.innerHTML = `
                    <div class="px-4 py-3 text-sm text-gray-500">
                        Tidak ada produk ditemukan.
                    </div>
                `;
                box.classList.remove('hidden');
                return;
            }

            box.innerHTML = items.map(item => `
                <button
                    type="button"
                    class="search-result-item w-full text-left flex items-center
                           px-4 py-2.5 text-sm hover:bg-gray-50 border-b last:border-b-0"
                    data-url="${item.url}"
                >
                    <div class="w-10 h-12 rounded-md bg-gray-100 overflow-hidden mr-3 flex-shrink-0">
                        ${item.image
                            ? `<img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">`
                            : `<div class="w-full h-full flex items-center justify-center text-xs text-gray-400">No img</div>`
                        }
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800 line-clamp-1">${item.name}</p>
                        <p class="text-xs text-gray-500">Rp ${item.price}</p>
                    </div>
                </button>
            `).join('');

            box.classList.remove('hidden');
        }

        async function doSearch(term) {
            if (!term || term.trim().length === 0) {
                clearResults();
                return;
            }

            try {
                const response = await fetch(`${searchUrl}?q=${encodeURIComponent(term)}`);
                if (!response.ok) throw new Error('Network error');

                const data = await response.json();
                renderResults(data);
            } catch (e) {
                console.error(e);
                clearResults();
            }
        }

        box.addEventListener('click', function (e) {
            const item = e.target.closest('.search-result-item');
            if (!item) return;

            const url = item.dataset.url;

            @auth
                if (url) {
                    window.location.href = url;   // user sudah login → langsung ke halaman produk
                }
            @else
                window.location.href = loginUrl;   // guest → paksa login dulu
            @endauth
        });

        input.addEventListener('input', function (e) {
            const term = e.target.value;

            clearTimeout(timer);
            timer = setTimeout(() => {
                doSearch(term);
            }, 300);
        });

        document.addEventListener('click', function (e) {
            if (!wrapper) return;
            if (!wrapper.contains(e.target)) {
                clearResults();
            }
        });
    })();
    </script>

</body>
</html>
