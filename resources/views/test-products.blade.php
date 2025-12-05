<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="max-w-6xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-6">Test Produk + Gambar</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($products as $product)
                @php
                    $thumb = $product->productImages->first();
                @endphp

                <div class="bg-white rounded-lg shadow p-4">
                    @if ($thumb)
                        <img
                            src="{{ asset($thumb->image) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-60 object-cover rounded mb-3"
                        >
                    @else
                        <div class="w-full h-60 bg-gray-300 flex items-center justify-center rounded mb-3">
                            <span class="text-gray-600 text-sm">No Image</span>
                        </div>
                    @endif

                    <h2 class="font-semibold text-lg">{{ $product->name }}</h2>
                    <p class="text-sm text-gray-600 mb-1">
                        Kategori ID: {{ $product->product_category_id }}
                    </p>
                    <p class="font-bold text-pink-700">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
