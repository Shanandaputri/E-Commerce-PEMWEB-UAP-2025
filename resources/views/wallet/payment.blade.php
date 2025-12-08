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

        {{-- Error Handling --}}
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                <ul class="text-sm">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('payment.confirm') }}" method="POST" class="space-y-5">
            @csrf

            {{-- VA Number --}}
            <div>
                <label class="block text-sm font-medium mb-1">Nomor VA</label>
                <input 
                    type="text"
                    name="va_number"
                    value="{{ old('va_number', $prefilledVa ?? '') }}"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-100"
                    placeholder="Masukkan nomor VA"
                    required
                >
            </div>

            {{-- Amount --}}
            <div>
                <label class="block text-sm font-medium mb-1">Nominal Pembayaran</label>
                <input 
                    type="number"
                    name="amount"
                    value="{{ old('amount', $prefilledAmount ?? '') }}"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-100"
                    placeholder="Masukkan nominal"
                    required
                >
            </div>

            {{-- Submit Button --}}
            <button
                type="submit"
                class="w-full bg-black text-white py-3 rounded-full text-lg font-semibold hover:bg-gray-800 transition"
            >
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
