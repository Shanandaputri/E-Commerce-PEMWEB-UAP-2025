<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - SHOP.CO</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">

{{-- NAVBAR SEDERHANA (copy dari homepage) --}}
    <header class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('customer.dashboard') }}" class="text-2xl font-bold">SHOP.CO</a>

            <nav class="flex items-center gap-6 text-sm">
                <a href="{{ route('customer.dashboard') }}" class="text-gray-600 hover:text-black">Home</a>
                <a href="#" class="text-gray-600 hover:text-black">Kategori</a>
                <a href="#" class="text-gray-600 hover:text-black">History</a>
            </nav>

            <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-black text-sm">
                Profile
            </a>
        </div>
    </header>

{{-- BREADCRUMB --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <p class="text-sm text-gray-600">
        <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>
        <span class="mx-2">></span>
        <span class="text-gray-900">Keranjang</span>
    </p>
</div>

{{-- MAIN CONTENT --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Your cart</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- CART ITEMS --}}
        <div class="lg:col-span-2 space-y-4">
            @forelse($carts as $cart)
                <div class="bg-white border rounded-2xl p-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset($cart->product->productImages->first()->image ?? 'images/noimage.png') }}"
                             alt="{{ $cart->product->name }}"
                             class="w-24 h-24 object-cover rounded-lg">

                        <div>
                            <h3 class="font-bold text-lg">{{ strtoupper($cart->product->name) }}</h3>
                            <p class="text-sm text-gray-600">
                                Size:
                                <span class="text-gray-900">{{ $cart->size ?? 'Large' }}</span>
                            </p>
                            <p class="text-sm text-gray-600">
                                Color:
                                <span class="text-gray-900">{{ $cart->color ?? 'White' }}</span>
                            </p>
                            <p class="font-bold text-lg mt-2">
                                {{ number_format($cart->product->price, 0, ',', '.') }} IDR
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        {{-- QUANTITY CONTROL --}}
                        <div class="flex items-center bg-gray-100 rounded-full px-4 py-2 space-x-4">
                            <form action="{{ route('cart.update', $cart) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ max(1, $cart->quantity - 1) }}">
                                <button type="submit" class="text-gray-600 hover:text-black">−</button>
                            </form>

                            <span class="font-semibold">{{ $cart->quantity }}</span>

                            <form action="{{ route('cart.update', $cart) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ $cart->quantity + 1 }}">
                                <button type="submit" class="text-gray-600 hover:text-black">+</button>
                            </form>
                        </div>

                        {{-- DELETE BUTTON --}}
                        <form action="{{ route('cart.destroy', $cart) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">Your cart is empty</p>
                    <a href="{{ route('home') }}"
                       class="inline-block mt-4 bg-black text-white px-6 py-3 rounded-full hover:bg-gray-800">
                        Continue Shopping
                    </a>
                </div>
            @endforelse
        </div>

        {{-- ORDER SUMMARY --}}
        @if($carts->isNotEmpty())
            <div class="bg-white border rounded-2xl p-6">
                <h2 class="text-xl font-bold mb-6">Order Summary</h2>

                <div class="space-y-4 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">
                            {{ number_format($subtotal, 0, ',', '.') }} IDR
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Delivery Fee</span>
                        <span class="font-semibold">
                            {{ number_format($deliveryFee, 0, ',', '.') }} IDR
                        </span>
                    </div>

                    <hr>

                    <div class="flex justify-between text-lg">
                        <span class="font-bold">Total</span>
                        <span class="font-bold">
                            {{ number_format($total, 0, ',', '.') }} IDR
                        </span>
                    </div>
                </div>

                {{-- PROMO CODE --}}
                <div class="mb-6">
                    <div class="flex items-center space-x-2">
                        <input type="text" placeholder="Add promo code"
                               class="flex-1 px-4 py-3 border rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        <button class="bg-black text-white px-6 py-3 rounded-full text-sm font-semibold hover:bg-gray-800">
                            Apply
                        </button>
                    </div>
                </div>

                {{-- CHECKOUT BUTTON --}}
                <a href="{{ route('checkout.index') }}"
                   class="block w-full bg-black text-white text-center py-4 rounded-full font-semibold hover:bg-gray-800 transition">
                    Go to Checkout →
                </a>
            </div>
        @endif
    </div>
</div>

</body>
</html>
