<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Kategori: {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('seller.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                        <input type="text" name="name" class="mt-1 block w-full border rounded p-2"
                               value="{{ old('name', $category->name) }}" required>
                        @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tagline</label>
                        <input type="text" name="tagline" class="mt-1 block w-full border rounded p-2"
                               value="{{ old('tagline', $category->tagline) }}">
                        @error('tagline') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" class="mt-1 block w-full border rounded p-2"
                                  rows="4">{{ old('description', $category->description) }}</textarea>
                        @error('description') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Gambar (opsional)</label>
                        @if ($category->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/'.$category->image) }}" alt="" class="h-16">
                            </div>
                        @endif
                        <input type="file" name="image" class="mt-1 block w-full">
                        @error('image') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <x-primary-button>Update</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
