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

    {{-- HEADER --}}
    @includeIf('partials.header')

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
                                    <span class="text-xs text-gray-400
                                        {{ (isset($category) && $category->id === $cat->id)
                                            ? 'text-gray-200'
                                            : '' }}">
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

                        {{-- (opsional) sort dropdown --}}
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
                        {{-- Kalau mau custom style kayak desain, kamu bisa override view pagination-nya --}}
                    </div>
                </section>
            </div>

            {{-- Banner Newsletter --}}
            <section class="mt-16">
                <div class="bg-black text-white rounded-3xl px-8 py-10 md:px-12 md:py-12 flex flex-col lg:flex-row items-center lg:justify-between gap-6">
                    <div class="max-w-xl">
                        <h2 class="text-2xl md:text-3xl font-bold uppercase tracking-tight">
                            Stay up to date about<br class="hidden md:block">
                            our latest offers
                        </h2>
                    </div>

                    <div class="w-full max-w-md space-y-3">
                        <div class="bg-white text-gray-800 rounded-full flex items-center px-4 py-2">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 12a4 4 0 10-8 0 4 4 0 008 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 14v7m-4-3h8"/>
                            </svg>
                            <input
                                type="email"
                                class="flex-1 bg-transparent border-none focus:ring-0 text-sm"
                                placeholder="Enter your email address"
                            >
                        </div>
                        <button class="w-full bg-white text-black font-semibold text-sm py-3 rounded-full hover:bg-gray-100 transition">
                            Subscribe to Newsletter
                        </button>
                    </div>
                </div>
            </section>

        </div>
    </main>

    {{-- FOOTER (kalau kamu sudah punya partial, ganti dengan @includeIf('partials.footer') --}}
    @includeIf('partials.footer')

</body>
</html>
