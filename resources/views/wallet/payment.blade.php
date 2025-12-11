<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran - SHOP.CO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">


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

    <div class="min-h-screen flex items-center justify-center p-4">
        
        <div class="bg-white rounded-3xl max-w-lg w-full p-8 shadow-lg">
            
            <h1 class="text-2xl font-bold text-center mb-4">Konfirmasi Pembayaran</h1>
            
            <p class="text-gray-600 text-center mb-6">
                Silakan masukkan <strong>Nomor Virtual Account</strong> dan <strong>Nominal Transfer</strong> untuk menyelesaikan pembayaran.
            </p>

            {{-- ERROR MESSAGE --}}
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4">
                    {{ session('error') }}
                </div>
            @endif

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- INFO VA --}}
            @if(isset($vaNumber) && $vaNumber)
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <p class="text-sm text-blue-800">
                        <strong>Nomor VA:</strong> {{ $vaNumber }}<br>
                        <strong>Nominal:</strong> Rp {{ number_format($vaAmount, 0, ',', '.') }}
                    </p>
                </div>
            @endif

            <form action="{{ route('payment.confirm') }}" method="POST">
                @csrf
                
                {{-- hidden buat identitas transaksi & mode --}}
                <input type="hidden" name="transaction_id" value="{{ $transactionId ?? '' }}">
                <input type="hidden" name="mode" value="{{ $mode ?? 'topup' }}">

                {{-- Nomor VA --}}
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2">Nomor VA</label>
                    <input 
                        type="text" 
                        name="va_number" 
                        value="{{ old('va_number', $vaNumber ?? '') }}"
                        placeholder="Masukkan nomor VA"
                        class="w-full bg-gray-100 border-0 rounded-2xl px-6 py-3 focus:outline-none focus:ring-2 focus:ring-black"
                        required
                    >
                    @error('va_number')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nominal Pembayaran --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-2">Nominal Pembayaran</label>
                    <input 
                        type="number" 
                        name="amount" 
                        value="{{ old('amount', $vaAmount ?? '') }}"
                        placeholder="Masukkan nominal"
                        class="w-full bg-gray-100 border-0 rounded-2xl px-6 py-3 focus:outline-none focus:ring-2 focus:ring-black"
                        min="10000"
                        required
                    >
                    @error('amount')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <button 
                    type="submit" 
                    class="w-full bg-black text-white py-4 rounded-full font-semibold hover:bg-gray-800 transition mb-3"
                >
                    Konfirmasi Pembayaran
                </button>

                <a 
                    href="{{ route('wallet.index') }}" 
                    class="block w-full text-center py-4 text-gray-600 hover:text-black transition"
                >
                    ← Kembali ke Wallet
                </a>
            </form>

        </div>

    </div>

</body>
</html>
