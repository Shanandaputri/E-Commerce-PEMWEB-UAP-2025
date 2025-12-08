<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Wallet - SHOP.CO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    {{-- NAVBAR SEDERHANA (copy dari homepage) --}}
    <header class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-2xl font-bold">SHOP.CO</a>

            <nav class="flex items-center gap-6 text-sm">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-black">Home</a>
                <a href="#" class="text-gray-600 hover:text-black">Kategori</a>
                <a href="#" class="text-gray-600 hover:text-black">History</a>
            </nav>

            <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-black text-sm">
                Profile
            </a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-10">

        <h1 class="text-sm text-gray-500 mb-2">e-wallet</h1>

        {{-- Kartu saldo --}}
        <section class="bg-white rounded-3xl p-8 shadow-sm mb-8">
            <h2 class="text-lg font-semibold mb-4">Saldo</h2>

            <div class="bg-gray-100 rounded-2xl py-10 text-center mb-6">
                <p class="text-4xl md:text-5xl font-bold tracking-wide">
                    {{ number_format($wallet->balance, 0, ',', '.') }} IDR
                </p>
            </div>

            <a href="{{ route('wallet.topup') }}"
               class="block w-full bg-black text-white text-center py-3.5 rounded-full font-semibold hover:bg-gray-800 transition">
                Top up
            </a>
        </section>

        {{-- Riwayat --}}
        <section class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Riwayat</h2>

            @forelse($transactions as $transaction)
                <div class="flex justify-between items-center py-4 border-b last:border-0">
                    <div>
                        <p class="font-semibold text-sm">
                            {{ ucfirst($transaction->transaction_type) }}
                            {{ $transaction->description }}
                        </p>

                        <p class="text-xs text-gray-500">
                            {{ $transaction->created_at->format('d M Y, H:i') }}
                        </p>

                        <div class="mt-2 flex items-center gap-2">
                            {{-- Badge status --}}
                            <span class="inline-block px-2 py-1 text-[11px] rounded-full
                                @if($transaction->status === 'success') bg-green-100 text-green-700 @endif
                                @if($transaction->status === 'pending') bg-yellow-100 text-yellow-700 @endif
                                @if($transaction->status === 'failed') bg-red-100 text-red-700 @endif
                            ">
                                {{ ucfirst($transaction->status) }}
                            </span>

                            {{-- Link bayar hanya untuk topup pending --}}
                            @if($transaction->transaction_type === 'topup' && $transaction->status === 'pending')
                                <a href="{{ route('payment.form', [
                                        'va'     => $transaction->va_number,
                                        'amount' => $transaction->amount,
                                    ]) }}"
                                   class="text-[11px] font-medium text-blue-600 hover:text-blue-800">
                                    Bayar sekarang
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Nominal --}}
                    <p class="text-lg font-bold text-green-600">
                        + {{ number_format($transaction->amount, 0, ',', '.') }} IDR
                    </p>
                </div>
            @empty
                <p class="text-center text-gray-500 py-6 text-sm">
                    Belum ada transaksi.
                </p>
            @endforelse
        </section>

    </main>

</body>
</html>
