<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up Saldo - E-Wallet</title>

    {{-- CSRF untuk fetch --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    {{-- NAVBAR --}}
    <header class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('customer.dashboard') }}" class="text-2xl font-bold">SHOP.CO</a>

            <nav class="flex items-center gap-6 text-sm">
                <a href="{{ route('customer.dashboard') }}" class="text-gray-600 hover:text-black">Home</a>
                <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-black">Kategori</a>
                <a href="{{ route('transactions.index') }}" class="text-gray-600 hover:text-black">History</a>
            </nav>

            <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-black text-sm">
                Profile
            </a>
        </div>
    </header>

    {{-- KONTEN TOP UP --}}
    <main class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-5xl mx-auto px-4">
            {{-- breadcrumb kecil --}}
            <p class="text-sm text-gray-500 mb-3">e-wallet</p>

            {{-- CARD TOP UP --}}
            <section class="bg-white rounded-3xl shadow-sm px-8 py-10 md:px-12 md:py-12">
                <h1 class="text-2xl md:text-3xl font-bold mb-8">Top Up</h1>

                <form id="topup-form" class="space-y-6">
                    {{-- Input Nominal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Nominal
                        </label>

                        <div class="relative">
                            <span class="absolute inset-y-0 left-5 flex items-center text-gray-500 text-sm">
                                Rp
                            </span>

                            <input
                                id="amount-input"
                                type="number"
                                name="amount"
                                min="10000"
                                max="10000000"
                                step="1000"
                                class="w-full rounded-3xl border border-gray-200 bg-gray-100 px-14 pr-6 py-4 text-base focus:outline-none focus:ring-2 focus:ring-black focus:border-black"
                                placeholder="0"
                                required
                            >

                            <span class="absolute inset-y-0 right-5 flex items-center text-gray-400 text-xs md:text-sm">
                                IDR
                            </span>
                        </div>

                        <p class="mt-2 text-xs text-gray-500">
                            Minimal top up Rp 10.000, maksimal Rp 10.000.000
                        </p>
                        <p id="amount-error" class="mt-1 text-xs text-red-500 hidden"></p>
                    </div>

                    {{-- Tombol Top Up --}}
                    <button
                        type="submit"
                        class="w-full bg-black text-white py-3 rounded-full text-sm md:text-base font-semibold hover:bg-gray-900 transition"
                        id="topup-button"
                    >
                        Top up
                    </button>

                    {{-- Error general dari server --}}
                    <p id="topup-server-error" class="text-sm text-red-500 text-center hidden"></p>
                </form>
            </section>
        </div>
    </main>

    {{-- POPUP HASIL TOP UP (VA) --}}
    <div id="va-modal"
         class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-3xl max-w-lg w-full mx-4 p-8 md:p-10 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">
                Top Up Berhasil Dibuat
            </h2>
            <p class="text-sm md:text-base text-gray-600 mb-1">
                Nomor Virtual Account kamu sudah siap digunakan.
            </p>
            <p class="text-sm md:text-base text-gray-600 mb-6">
                Silakan lakukan transfer sesuai nominal yang dimasukkan.
            </p>

            <p class="text-sm font-medium text-left mb-2">Nomor VA:</p>

            <div class="bg-gray-100 rounded-2xl px-6 py-4 mb-6 text-left">
                <p id="va-number-display"
                   class="text-lg md:text-xl font-semibold tracking-widest text-gray-800 break-all">
                    1234 5678 9012
                </p>
                <p id="va-amount-display"
                   class="mt-1 text-xs text-gray-500">
                    Nominal: -
                </p>
            </div>

            {{-- Tombol Salin --}}
            <button
                id="copy-va-btn"
                class="w-full bg-black text-white py-3 rounded-full font-semibold text-sm md:text-base hover:bg-gray-900 transition mb-4"
            >
                Salin kode
            </button>

            {{-- Tombol navigasi --}}
            <div class="flex flex-col gap-2 text-sm">
                <button
                    id="go-pay-btn"
                    class="w-full border border-gray-300 rounded-full py-2.5 font-medium hover:bg-gray-50 transition"
                    type="button"
                >
                    Pergi ke Halaman Pembayaran
                </button>

                <button
                    id="back-wallet-btn"
                    class="w-full text-gray-500 hover:text-gray-800 font-medium py-2"
                    type="button"
                >
                    Kembali ke Wallet
                </button>
            </div>
        </div>
    </div>

    {{-- JS TOP UP --}}
    <script>
        const form              = document.getElementById('topup-form');
        const amountInput       = document.getElementById('amount-input');
        const amountError       = document.getElementById('amount-error');
        const serverError       = document.getElementById('topup-server-error');
        const topupButton       = document.getElementById('topup-button');

        const vaModal           = document.getElementById('va-modal');
        const vaNumberDisplay   = document.getElementById('va-number-display');
        const vaAmountDisplay   = document.getElementById('va-amount-display');
        const copyVaBtn         = document.getElementById('copy-va-btn');
        const goPayBtn          = document.getElementById('go-pay-btn');
        const backWalletBtn     = document.getElementById('back-wallet-btn');

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        let lastVa = null;
        let lastAmount = null;
        let lastTransactionId = null;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            amountError.classList.add('hidden');
            serverError.classList.add('hidden');
            serverError.textContent = '';

            const rawAmount = amountInput.value ? parseInt(amountInput.value, 10) : 0;

            if (isNaN(rawAmount) || rawAmount < 10000 || rawAmount > 10000000) {
                amountError.textContent = 'Nominal harus antara 10.000 dan 10.000.000.';
                amountError.classList.remove('hidden');
                return;
            }

            topupButton.disabled = true;
            topupButton.textContent = 'Memproses...';

            try {
                const response = await fetch('{{ route('wallet.topup.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ amount: rawAmount })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Terjadi kesalahan saat memproses top up.');
                }

                // SIMPAN SEMUA DATA
                lastVa = data.va_number;
                lastAmount = data.amount;
                lastTransactionId = data.transaction_id;

                console.log('Transaction ID:', lastTransactionId);

                vaNumberDisplay.textContent = data.va_number;
                vaAmountDisplay.textContent = 'Nominal: Rp ' + Number(data.amount).toLocaleString('id-ID');

                vaModal.classList.remove('hidden');
                vaModal.classList.add('flex');
            } catch (err) {
                console.error(err);
                serverError.textContent = err.message;
                serverError.classList.remove('hidden');
            } finally {
                topupButton.disabled = false;
                topupButton.textContent = 'Top up';
            }
        });

        copyVaBtn.addEventListener('click', async () => {
            if (!lastVa) return;
            try {
                await navigator.clipboard.writeText(lastVa);
                copyVaBtn.textContent = 'Disalin ✔';
                setTimeout(() => {
                    copyVaBtn.textContent = 'Salin kode';
                }, 1500);
            } catch {
                alert('Gagal menyalin kode VA.');
            }
        });

        goPayBtn.addEventListener('click', () => {
            if (!lastVa || !lastAmount || !lastTransactionId) {
                alert('Data tidak lengkap!');
                console.error('Missing data:', { lastVa, lastAmount, lastTransactionId });
                return;
            }
            
            const url = `{{ route('payment.form') }}?va=${encodeURIComponent(lastVa)}&amount=${encodeURIComponent(lastAmount)}&transaction_id=${encodeURIComponent(lastTransactionId)}`;
            
            console.log('Redirecting to:', url);
            window.location.href = url;
        });

        backWalletBtn.addEventListener('click', () => {
            window.location.href = `{{ route('wallet.index') }}`;
        });

        vaModal.addEventListener('click', (e) => {
            if (e.target === vaModal) {
                window.location.href = `{{ route('wallet.index') }}`;
            }
        });
    </script>

</body>
</html>
