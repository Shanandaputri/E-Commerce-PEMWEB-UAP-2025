@extends('seller.layouts.app')

@section('title', 'Produk Toko')
@section('page-title', 'Produk Toko')

@section('content')
    <div class="max-w-6xl mx-auto">

        @if (session('success'))
            <div class="mb-4 px-4 py-2 bg-green-50 text-green-700 text-sm rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if (session('status'))
            <div class="mb-4 px-4 py-2 bg-green-50 text-green-700 text-sm rounded-md">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-6">

            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Daftar Produk</h3>
                <a href="{{ route('seller.products.create') }}" class="text-blue-600 underline">
                    + Tambah Produk
                </a>
            </div>

            <table class="w-full border text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2 text-left">Nama</th>
                        <th class="border p-2 text-left">Kategori</th>
                        <th class="border p-2 text-right">Harga</th>
                        <th class="border p-2 text-right">Stok</th>
                        <th class="border p-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="border p-2">{{ $product->name }}</td>
                            <td class="border p-2">{{ $product->productCategory->name ?? '-' }}</td>
                            <td class="border p-2 text-right">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="border p-2 text-right">{{ $product->stock }}</td>
                            <td class="border p-2">
                                <a href="{{ route('seller.products.edit', $product) }}"
                                   class="text-blue-600 underline">Edit</a>

                                <form action="{{ route('seller.products.destroy', $product) }}"
                                      method="POST" class="inline-block ml-2"
                                      onsubmit="return confirm('Hapus produk?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 underline">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection