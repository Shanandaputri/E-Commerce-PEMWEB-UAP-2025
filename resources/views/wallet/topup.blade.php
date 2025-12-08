<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Top Up - SHOP.CO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    {{-- Header --}}
    <header class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-2xl font-bold">SHOP.CO</a>
            <div class="flex items-center gap-4">
                <a href="{{ route('wallet.index') }}" class="text-gray-600 hover:text-black">← Back to Wallet</a>
            </div>
        </div>
    </header>

    <div class="max-w-2xl mx-auto px-4 py-8">
        
        <h1 class="text-2xl font-bold mb-2">e-wallet</h1>
        
        {{-- Top Up Form --}}
        <div class="bg-white rounded-2xl p-8 shadow-sm">
            <h2 class="text-xl font-bold mb-6">Top Up</h2>
            
            <form id="topup-form">
                @csrf
                
                <label class="block mb-2 text-sm font-medium">Nominal</label>
                <input 
                    type="number" 
                    name="amount" 
                    id="amount"
                    placeholder="0 IDR" 
                    class="w-full bg-gray-100 border-0 rounded-2xl px-6 py-4 text-lg mb-6 focus:outline-none focus:ring-2 focus:ring-black"
                    min="10000"
                    step="1000"
                    required
                >

                <button 
                    type="submit" 
                    class="w-full bg-black text-white py-4 rounded-full font-semibold hover:bg-gray-800 transition"
                >
                    Top up
                </button>
            </form>
        </div>

    </div>

    {{-- Modal Pop-up VA --}}
    <div id="va-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-8">
            <h3 class="text-2xl font-bold mb-4">Top Up Berhasil Dibuat</h3>
            
            <p class="text-gray-600 mb-2">Nomor Virtual Account kamu sudah siap digunakan.</p>
            <p class="text-gray-600 mb-4">Silakan lakukan transfer sesuai nominal yang dimasukkan.</p>
            
            <label class="block text-sm font-medium mb-2">Nomor VA:</label>
            <div class="bg-gray-100 rounded-2xl px-6 py-4 mb-6">
                <p id="va-number" class="text-2xl font-mono tracking-wider"></p>
            </div>

            <button 
                onclick="copyVA()" 
                class="w-full bg-gray-200 text-black py-3 rounded-full font-semibold mb-3 hover:bg-gray-300 transition"
            >
                Salin kode
            </button>

            <button 
                onclick="simulatePayment()" 
                class="w-full bg-black text-white py-3 rounded-full font-semibold hover:bg-gray-800 transition"
            >
                Simulasi Pembayaran (Demo)
            </button>
        </div>
    </div>

    <script>
        let currentTransactionId = null;

        document.getElementById('topup-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const amount = document.getElementById('amount').value;
            
            if (amount < 10000) {
                alert('Minimum top up adalah Rp 10.000');
                return;
            }

            try {
                const response = await fetch('{{ route("wallet.topup.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ amount: amount })
                });

                const data = await response.json();

                if (data.success) {
                    // Tampilkan modal VA
                    document.getElementById('va-number').textContent = data.va_number;
                    document.getElementById('va-modal').classList.remove('hidden');
                    currentTransactionId = data.transaction_id;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan, silakan coba lagi');
            }
        });

        function copyVA() {
            const vaNumber = document.getElementById('va-number').textContent;
            navigator.clipboard.writeText(vaNumber);
            alert('Nomor VA berhasil disalin!');
        }

        async function simulatePayment() {
            try {
                const response = await fetch('{{ route("wallet.confirm") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ transaction_id: currentTransactionId })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Pembayaran berhasil! Saldo kamu telah ditambahkan.');
                    window.location.href = '{{ route("wallet.index") }}';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        }
    </script>

</body>
</html>