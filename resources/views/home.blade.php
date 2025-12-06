<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Homepage
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-4">Daftar Produk</h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($products as $product)
                        <div class="border rounded p-2">
                            @php
                                $thumb = $product->productImages->first();
                            @endphp

                            @if ($thumb)
                                <img src="{{ asset('storage/'.$thumb->image) }}" class="mb-2">
                            @endif

                            <div class="font-semibold text-sm">{{ $product->name }}</div>
                            <div class="text-xs text-gray-600">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
