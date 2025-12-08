<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran VA - E-Wallet</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">

<div class="max-w-xl mx-auto mt-16 p-8 bg-white rounded-2xl shadow">
    <h1 class="text-2xl font-bold text-center mb-6">Konfirmasi Pembayaran</h1>

    <p class="text-gray-600 mb-4">
        Silakan masukkan <strong>Nomor Virtual Account</strong> dan
        <strong>Nominal Transfer</strong> untuk menyelesaikan pembayaran.
    </p>

    {{-- ERROR --}}
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
            <ul class="text-sm">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- INFO VA --}}
    @if(isset($vaNumber) && $vaNumber)
        <div class="mb-4 p-3 bg-blue-100 text-blue-700 rounded-lg">
            Nomor Virtual Account Anda:
            <strong>{{ $vaNumber }}</strong><br>
            Nominal yang harus dibayar:
            <strong>{{ number_format($vaAmount, 0, ',', '.') }} IDR</strong>
        </div>
    @endif

    <form action="{{ route('payment.confirm') }}" method="POST" class="space-y-5">
        @csrf

        {{-- VA Number --}}
        <div>
            <label class="block text-sm font-medium mb-1">Nomor VA</label>
            <input type="text" name="va_number"
                   value="{{ old('va_number', $vaNumber) }}"
                   class="w-full px-4 py-3 bg-gray-100 rounded-lg"
                   placeholder="Masukkan nomor VA">
        </div>

        {{-- Amount --}}
        <div>
            <label class="block text-sm font-medium mb-1">Nominal Pembayaran</label>
            <input type="number" name="amount"
                   class="w-full px-4 py-3 bg-gray-100 rounded-lg"
                   placeholder="Masukkan nominal {{ $vaAmount ? '(' . number_format($vaAmount, 0, ',', '.') . ' IDR)' : '' }}">
        </div>

        <button
            type="submit"
            class="w-full bg-black text-white py-3 rounded-full text-lg font-semibold hover:bg-gray-800 transition">
            Konfirmasi Pembayaran
        </button>

        <a href="{{ route('wallet.index') }}"
           class="block text-center text-sm text-gray-600 hover:underline mt-3">
            ← Kembali ke Wallet
        </a>
    </form>
</div>

</body>
</html>
