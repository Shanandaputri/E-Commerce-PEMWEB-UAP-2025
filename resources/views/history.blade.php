<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>History</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

{{-- NAVBAR SEDERHANA --}}
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

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-xl font-bold mb-6">History</h1>

    {{-- TAB --}}
    <div class="border-b flex space-x-10 mb-6">
        <button id="tab-completed"
                class="pb-2 border-b-2 border-black font-semibold">
            Selesai
        </button>

        <button id="tab-canceled"
                class="pb-2 text-gray-500 hover:text-black transition">
            Dibatalkan
        </button>
    </div>

    {{-- SELESAI --}}
    <div id="content-completed">
        @forelse($completed as $trx)
            @foreach($trx->details as $detail)
                <div class="bg-white rounded-xl shadow-sm border p-4 mb-4 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset($detail->product->productImages->first()->image ?? 'images/noimage.png') }}"
                             class="w-20 h-20 object-cover rounded-md">
                        <div>
                            <p class="font-bold uppercase">{{ $detail->product->name }}</p>
                            <p class="text-sm text-gray-500">Size: {{ $detail->size ?? '-' }}</p>
                            <p class="text-sm text-gray-500">Color: {{ $detail->color ?? '-' }}</p>
                            <p class="font-bold mt-1">
                                {{ number_format($detail->product->price, 0, ',', '.') }} IDR
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('product.show', $detail->product->slug) }}"
                       class="bg-black text-white px-5 py-2 rounded-full hover:bg-gray-800 transition">
                        Beli lagi
                    </a>
                </div>
            @endforeach
        @empty
            <p class="text-gray-500">Belum ada transaksi selesai.</p>
        @endforelse
    </div>

    {{-- DIBATALKAN --}}
    <div id="content-canceled" class="hidden">
        @forelse($canceled as $trx)
            @foreach($trx->details as $detail)
                <div class="bg-gray-100 rounded-xl border p-4 mb-4 flex items-center justify-between opacity-80">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset($detail->product->productImages->first()->image ?? 'images/noimage.png') }}"
                             class="w-20 h-20 object-cover rounded-md">
                        <div>
                            <p class="font-bold uppercase">{{ $detail->product->name }}</p>
                            <p class="text-sm text-gray-500">Pesanan dibatalkan</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @empty
            <p class="text-gray-500">Tidak ada transaksi dibatalkan.</p>
        @endforelse
    </div>
</div>

<script>
    const tabCompleted = document.getElementById('tab-completed');
    const tabCanceled  = document.getElementById('tab-canceled');
    const contentCompleted = document.getElementById('content-completed');
    const contentCanceled  = document.getElementById('content-canceled');

    tabCompleted.addEventListener('click', () => {
        contentCompleted.classList.remove('hidden');
        contentCanceled.classList.add('hidden');
        tabCompleted.classList.add('border-black');
        tabCanceled.classList.remove('border-black');
    });

    tabCanceled.addEventListener('click', () => {
        contentCompleted.classList.add('hidden');
        contentCanceled.classList.remove('hidden');
        tabCanceled.classList.add('border-black');
        tabCompleted.classList.remove('border-black');
    });
</script>

</body>
</html>
