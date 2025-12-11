@extends('seller.layouts.app')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
    <div class="bg-white shadow-sm border border-gray-100 rounded-2xl p-6">

        @if(session('success'))
            <div class="mb-4 px-4 py-2 bg-green-50 text-green-700 text-sm rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Daftar Kategori</h2>

            <a href="{{ route('seller.categories.create') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-black text-white hover:bg-gray-800">
                + Tambah Kategori
            </a>
        </div>

        <table class="w-full text-sm border border-gray-100 rounded-lg overflow-hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2 border-b">Nama</th>
                    <th class="text-left px-4 py-2 border-b">Deskripsi</th>
                    <th class="text-right px-4 py-2 border-b">Jumlah Produk</th>
                    <th class="text-left px-4 py-2 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border-b font-medium">
                        {{ $category->name }}
                    </td>
                    <td class="px-4 py-2 border-b text-gray-600">
                        {{ Str::limit($category->description, 80) }}
                    </td>
                    <td class="px-4 py-2 border-b text-right">
                        {{ $category->products_count ?? 0 }}
                    </td>
                    <td class="px-4 py-2 border-b">
                        <a href="{{ route('seller.categories.edit', $category) }}"
                           class="text-blue-600 hover:underline text-sm">Edit</a>

                        <form action="{{ route('seller.categories.destroy', $category) }}"
                              method="POST"
                              class="inline-block ml-2"
                              onsubmit="return confirm('Hapus kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 hover:underline text-sm">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                        Belum ada kategori.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
@endsection