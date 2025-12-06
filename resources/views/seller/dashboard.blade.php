<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Seller Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Hai, {{ auth()->user()->name }}!</p>
                    <p>Toko: {{ auth()->user()->store->name ?? '-' }}</p>

                    <div class="mt-4">
                        <ul class="list-disc pl-5">
                            <li><a href="#">Kelola Profil Toko</a></li>
                            <li><a href="#">Kelola Kategori</a></li>
                            <li><a href="#">Kelola Produk</a></li>
                            <li><a href="#">Pesanan Masuk</a></li>
                            <li><a href="#">Saldo Toko & Riwayat</a></li>
                            <li><a href="#">Penarikan Dana</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
