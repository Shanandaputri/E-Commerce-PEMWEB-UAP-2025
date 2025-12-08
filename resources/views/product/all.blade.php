<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle ?? 'All Products' }} - {{ config('app.name', 'SHOP.CO') }}</title>

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
<body class="bg-white">

    {{-- HEADER --}}
    @includeIf('partials.header')

    {{-- BREADCRUMB --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:underline">Home</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $pageTitle ?? 'All Products' }}</span>
        </div>
    </div>

    {{-- PRODUCTS SECTION --}}
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Page Title --}}
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold font-anton">
                    {{ strtoupper($pageTitle ?? 'ALL PRODUCTS') }}
                </h1>
                <p class="text-gray-600 mt-2">Showing {{ $products->count() }} of {{ $products->total() }} products</p>
            </div>

            <div class="flex gap-8">
                
                {{-- SIDEBAR FILTER (Optional) --}}
                <aside class="hidden lg:block w-64 flex-shrink-0">
                    <div class="bg-white border rounded-2xl p-6 sticky top-4">
                        <h3 class="font-bold text-lg mb-4">Filters</h3>
                        
                        {{-- Categories --}}
                        <div class="mb-6">
                            <h4 class="font-semibold mb-3 text-sm">Categories</h4>
                            <div class="space-y-2">
                                <a href="{{ route('products.all') }}" class="block text-sm text-gray-600 hover:text-black">
                                    All Categories
                                </a>
                                @foreach($categories as $category)
                                    <a href="?category={{ $category->id }}" class="block text-sm text-gray-600 hover:text-black">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Price Range --}}
                        <div class="mb-6">
                            <h4 class="font-semibold mb-3 text-sm">Price Range</h4>
                            <div class="space-y-2">
                                <label class="flex items-center text-sm">
                                    <input type="checkbox" class="mr-2">
                                    <span>Under Rp 100.000</span>
                                </label>
                                <label class="flex items-center text-sm">
                                    <input type="checkbox" class="mr-2">
                                    <span>Rp 100.000 - Rp 500.000</span>
                                </label>
                                <label class="flex items-center text-sm">
                                    <input type="checkbox" class="mr-2">
                                    <span>Rp 500.000 - Rp 1.000.000</span>
                                </label>
                                <label class="flex items-center text-sm">
                                    <input type="checkbox" class="mr-2">
                                    <span>Above Rp 1.000.000</span>
                                </label>
                            </div>
                        </div>

                        <button class="w-full bg-black text-white py-2 rounded-full text-sm font-semibold hover:bg-gray-800 transition">
                            Apply Filters
                        </button>
                    </div>
                </aside>

                {{-- PRODUCTS GRID --}}
                <div class="flex-1">
                    
                    {{-- Sort Options --}}
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-sm text-gray-600">
                            {{ $products->total() }} Products Found
                        </p>
                        <select class="text-sm border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                            <option>Most Popular</option>
                            <option>Newest</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                        </select>
                    </div>

                    {{-- Products Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @forelse($products as $product)
                            <a href="{{ route('product.show', $product->slug) }}" class="group cursor-pointer block">
                                <div class="aspect-[3/4] bg-gray-100 rounded-2xl overflow-hidden mb-3">
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
                                <h3 class="font-semibold mb-1 text-sm">{{ $product->name }}</h3>
                                <div class="flex items-center mb-1">
                                    <div class="flex text-yellow-400 text-xs">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-600 ml-1">4.5/5</span>
                                </div>
                                <p class="font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </a>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-500">No products found.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    @includeIf('partials.footer')

</body>
</html>