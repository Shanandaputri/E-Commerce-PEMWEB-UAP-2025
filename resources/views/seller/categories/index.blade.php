<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kategori Produk
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if (session('status'))
                    <div class="mb-4 text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Daftar Kategori</h3>
                    <a href="{{ route('seller.categories.create') }}" class="text-blue-600 underline">
                        + Tambah Kategori
                    </a>
                </div>

                <table class="w-full border text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 border text-left">Nama</th>
                            <th class="p-2 border text-left">Tagline</th>
                            <th class="p-2 border text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td class="border p-2">{{ $category->name }}</td>
                                <td class="border p-2">{{ $category->tagline }}</td>
                                <td class="border p-2">
                                    <a href="{{ route('seller.categories.edit', $category) }}"
                                       class="text-blue-600 underline">Edit</a>

                                    <form action="{{ route('seller.categories.destroy', $category) }}"
                                          method="POST" class="inline-block ml-2"
                                          onsubmit="return confirm('Hapus kategori?')">
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
                                <td colspan="3" class="p-4 text-center">Belum ada kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
