@extends('seller.layouts.app')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@section('content')
    <div class="max-w-xl bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

        <a href="{{ route('seller.categories.index') }}"
           class="text-sm text-gray-500 hover:text-gray-800 mb-4 inline-flex items-center">
            ← Kembali ke daftar kategori
        </a>

        <h2 class="text-lg font-semibold mb-4">Tambah Kategori Baru</h2>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('seller.categories.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Kategori
                </label>
                <input type="text"
                       name="name"
                       class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black/70"
                       value="{{ old('name') }}"
                       required>
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi (opsional)
                </label>
                <textarea name="description"
                          rows="3"
                          class="mt-1 block w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black/70">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 rounded-full bg-black text-white text-sm font-semibold hover:bg-gray-800">
                Simpan
            </button>
        </form>
    </div>
@endsection