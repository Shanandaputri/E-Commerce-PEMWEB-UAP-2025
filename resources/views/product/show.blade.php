{{-- resources/views/product/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 grid md:grid-cols-2 gap-8">
                
                {{-- KIRI: GAMBAR --}}
                <div>
                    @if ($thumb = $product->productImages->first())
                        <img src="{{ asset('storage/'.$thumb->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full rounded mb-4">
                    @else
                        <div class="w-full h-64 bg-gray-100 flex items-center justify-center text-gray-400 rounded">
                            No Image
                        </div>
                    @endif

                    @if ($product->productImages->count() > 1)
                        <div class="flex gap-2 flex-wrap">
                            @foreach ($product->productImages as $img)
                                <img src="{{ asset('storage/'.$img->image) }}" 
                                     class="h-16 w-16 object-cover border rounded">
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- KANAN: INFO PRODUK --}}
                <div>
                    <h1 class="text-2xl font-bold mb-2">{{ $product->name }}</h1>

                    <p class="text-gray-500 text-sm mb-1">
                        Kategori: {{ $product->productCategory->name ?? '-' }}
                    </p>

                    <p class="text-gray-500 text-sm mb-1">
                        Toko: {{ $product->store->name ?? '-' }}
                    </p>

                    <p class="text-lg font-semibold mb-4">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <p class="mb-4 text-sm text-gray-700">
                        {{ $product->description }}
                    </p>

                    {{-- Nanti ini disambung ke checkout --}}
                    <form action="#" method="POST">
                        @csrf

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah
                            </label>
                            <input type="number" name="qty" value="1" min="1"
                                class="w-24 border rounded px-2 py-1 mb-4">

                            {{-- Nanti kita sambung ke /checkout di Step 2 --}}
                            <button type="button"
                                    class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                                Beli Sekarang
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
