<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Wallet - SHOP.CO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    {{-- Header --}}
    <header class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-2xl font-bold">SHOP.CO</a>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-black">Home</a>
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-black">Dashboard</a>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 py-8">
        
        <h1 class="text-2xl font-bold mb-2">e-wallet</h1>
        
        {{-- Saldo Card --}}
        <div class="bg-white rounded-2xl p-8 shadow-sm mb-6">
            <h2 class="text-xl font-bold mb-4">Saldo</h2>
            
            <div class="bg-gray-100 rounded-2xl p-8 text-center mb-6">
                <p class="text-5xl font-bold">{{ number_format($wallet->balance, 0, ',', '.') }} IDR</p>
            </div>

            <a href="{{ route('wallet.topup') }}" class="block w-full bg-black text-white text-center py-4 rounded-full font-semibold hover:bg-gray-800 transition">
                Top up
            </a>
        </div>

        {{-- Riwayat Transaksi --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <h2 class="text-xl font-bold mb-4">Riwayat</h2>
            
            @forelse($transactions as $transaction)
                <div class="flex justify-between items-center py-4 border-b last:border-0">
                    <div>
                        <p class="font-semibold">{{ ucfirst($transaction->transaction_type) }} {{ $transaction->description }}</p>
                        <p class="text-sm text-gray-500">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                        <span class="inline-block px-2 py-1 text-xs rounded-full mt-1
                            {{ $transaction->status === 'success' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $transaction->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                        ">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                    <p class="text-lg font-bold {{ $transaction->transaction_type === 'topup' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $transaction->transaction_type === 'topup' ? '+' : '-' }} {{ number_format($transaction->amount, 0, ',', '.') }} IDR
                    </p>
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">Belum ada transaksi</p>
            @endforelse
        </div>

    </div>

</body>
</html>