<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SHOP.CO</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=integral-cf:400,700|satoshi:400,500,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Satoshi', system-ui, -apple-system, BlinkMacSystemFont, sans-serif; }
        .font-integral { font-family: 'Integral CF', system-ui, -apple-system, BlinkMacSystemFont, sans-serif; }
    </style>
</head>
<body class="bg-white">

{{-- HEADER --}}
<header class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ route('customer.dashboard') }}" class="text-2xl font-bold">
            SHOP.CO
        </a>

        {{-- Profile --}}
        <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-black text-sm">
            Profile
        </a>
    </div>
</header>

{{-- BREADCRUMB --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <p class="text-sm text-gray-600">
        <a href="{{ route('customer.dashboard') }}" class="hover:text-gray-900">Home</a>
        <span class="mx-2">></span>
        <a href="{{ route('cart.index') }}" class="hover:text-gray-900">Keranjang</a>
        <span class="mx-2">></span>
        <span class="text-gray-900">Checkout</span>
    </p>
</div>

{{-- MAIN CONTENT --}}
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST"
          class="bg-white border rounded-2xl p-8 space-y-6">
        @csrf

        {{-- ALAMAT --}}
        <div>
            <h2 class="text-xl font-bold mb-4">Alamat</h2>

            <div class="mb-4">
                <label class="block text-sm text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="recipient_name" required
                       class="w-full px-4 py-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                       placeholder="Nama Lengkap">
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-700 mb-2">Alamat</label>
                <textarea name="address" required rows="3"
                          class="w-full px-4 py-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                          placeholder="Alamat"></textarea>
            </div>
        </div>

        {{-- PEMBAYARAN --}}
        <div>
            <label class="block text-sm text-gray-700 mb-2">Pembayaran</label>
            <select name="payment_method" required
                    class="w-full px-4 py-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-black appearance-none">
                <option value="">Select payment method</option>
                <option value="wallet">
                    E-Wallet (Saldo: Rp {{ number_format($userBalance->balance ?? 0, 0, ',', '.') }})
                </option>
                <option value="va">Transfer Bank / VA</option>
            </select>
        </div>

        {{-- TOTAL --}}
        <div class="pt-4 border-t">
            <div class="flex justify-between items-center text-xl font-bold">
                <span>Total</span>
                <span>{{ number_format($total, 0, ',', '.') }} IDR</span>
            </div>
        </div>

        {{-- CHECKOUT BUTTON --}}
        <button type="submit"
                class="w-full bg-black text-white py-4 rounded-full font-semibold hover:bg-gray-800 transition">
            Checkout
        </button>
    </form>
</div>

</body>
</html>
