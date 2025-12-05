<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Toko
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('status'))
                    <div class="mb-4 text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('store.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Toko</label>
                        <input type="text" name="name" class="mt-1 block w-full border rounded p-2"
                               value="{{ old('name') }}" required>
                        @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="about" class="mt-1 block w-full border rounded p-2">{{ old('about') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                        <input type="text" name="phone" class="mt-1 block w-full border rounded p-2"
                               value="{{ old('phone') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kota</label>
                        <input type="text" name="city" class="mt-1 block w-full border rounded p-2"
                               value="{{ old('city') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea name="address" class="mt-1 block w-full border rounded p-2" required>{{ old('address') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kode Pos</label>
                        <input type="text" name="postal_code" class="mt-1 block w-full border rounded p-2"
                               value="{{ old('postal_code') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Logo (opsional)</label>
                        <input type="file" name="logo" class="mt-1 block w-full">
                        @error('logo') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <x-primary-button>Daftar Toko</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
