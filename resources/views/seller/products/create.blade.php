<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Produk
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                        <input type="text" name="name" class="mt-1 block w-full border rounded p-2"
                               value="{{ old('name') }}" required>
                        @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select name="product_category_id" class="mt-1 block w-full border rounded p-2" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('product_category_id') == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_category_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" class="mt-1 block w-full border rounded p-2" rows="4">{{ old('description') }}</textarea>
                        @error('description') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kondisi</label>
                        <select name="condition" class="mt-1 block w-full border rounded p-2" required>
                            <option value="new" @selected(old('condition') == 'new')>Baru</option>
                            <option value="second" @selected(old('condition') == 'second')>Bekas</option>
                        </select>
                        @error('condition') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga</label>
                            <input type="number" name="price" class="mt-1 block w-full border rounded p-2"
                                   value="{{ old('price') }}" required>
                            @error('price') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Berat (gram)</label>
                            <input type="number" name="weight" class="mt-1 block w-full border rounded p-2"
                                   value="{{ old('weight') }}" required>
                            @error('weight') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stok</label>
                            <input type="number" name="stock" class="mt-1 block w-full border rounded p-2"
                                   value="{{ old('stock') }}" required>
                            @error('stock') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Gambar Produk (bisa lebih dari satu)</label>
                        <input type="file" name="images[]" multiple class="mt-1 block w-full">
                        <p class="text-xs text-gray-500 mt-1">Gambar pertama akan dijadikan thumbnail.</p>
                        @error('images.*') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <x-primary-button>Simpan</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>