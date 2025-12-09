<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SHOP.CO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">

{{-- HEADER --}}
<header class="border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('customer.dashboard') }}" class="text-2xl font-bold font-integral">SHOP.CO</a>
            <nav class="hidden md:flex md:space-x-8">
                <a href="{{ route('customer.dashboard') }}" class="text-gray-600 hover:text-black">Home</a>
                <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-black">Kategori</a>
                <a href="{{ route('transactions.index') }}" class="text-gray-600 hover:text-black">History</a>
            </nav>
        </div>
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
