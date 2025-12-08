{{-- resources/views/seller/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Seller - @yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

<div class="flex">

    {{-- SIDEBAR --}}
    <aside class="w-60 bg-white border-r border-gray-200 min-h-screen px-6 py-6">
        <div class="font-bold text-lg mb-8">
            Logo Toko
        </div>

        <nav class="space-y-2 text-sm">
            <a href="{{ route('seller.dashboard') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg
                      {{ request()->routeIs('seller.dashboard') ? 'bg-gray-100 font-semibold' : 'hover:bg-gray-50' }}">
                <span>🏠</span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('seller.categories.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg
                      {{ request()->routeIs('seller.categories.*') ? 'bg-gray-100 font-semibold' : 'hover:bg-gray-50' }}">
                <span>📁</span>
                <span>Manajemen Kategori</span>
            </a>

            <a href="{{ route('seller.products.index') ?? '#' }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50">
                <span>🛍️</span>
                <span>Manajemen Produk</span>
            </a>

            <a href="#"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50">
                <span>📦</span>
                <span>Manajemen Pesanan</span>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50">
                <span>👤</span>
                <span>Profil</span>
            </a>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 px-10 py-8">
        {{-- header halaman --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-semibold">
                @yield('page-title', 'Dashboard')
            </h1>

            <div class="text-sm text-gray-500">
                Seller Panel
            </div>
        </div>

        {{-- flash message --}}
        @if(session('success'))
            <div class="mb-4 px-4 py-2 bg-green-50 text-green-700 text-sm rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

</body>
</html>
