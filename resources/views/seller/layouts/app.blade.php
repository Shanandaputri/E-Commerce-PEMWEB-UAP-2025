<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Seller - @yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body, * {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

<div class="flex">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white border-r border-gray-200 min-h-screen px-6 py-7 flex flex-col justify-between">

        {{-- LOGO --}}
        <div>
            <div class="flex items-center mb-8">
                <a href="{{ auth()->check() ? route('customer.dashboard') : route('login') }}" 
                class="text-2xl font-bold font-integral tracking-wide">
                SHOP.CO
                </a>
            </div>

            {{-- MENU --}}
            <nav class="space-y-1.5 text-sm flex flex-col">

                {{-- DASHBOARD --}}
                <a href="{{ route('seller.dashboard') }}"
                class="flex items-center gap-2 px-4 py-2.5 rounded-lg
                {{ request()->routeIs('seller.dashboard') 
                        ? 'bg-gray-100 font-semibold text-blue-600 border-l-4 border-blue-600 -ml-1 pl-5' 
                        : 'hover:bg-gray-50 text-gray-700' }}">
                    <span>Dashboard</span>
                </a>

                {{-- KATEGORI --}}
                <a href="{{ route('seller.categories.index') }}"
                class="flex items-center gap-2 px-4 py-2.5 rounded-lg
                {{ request()->routeIs('seller.categories.*') 
                        ? 'bg-gray-100 font-semibold text-blue-600 border-l-4 border-blue-600 -ml-1 pl-5' 
                        : 'hover:bg-gray-50 text-gray-700' }}">
                    <span>Manajemen Kategori</span>
                </a>

                {{-- PRODUK --}}
                <a href="{{ route('seller.products.index') }}"
                class="flex items-center gap-2 px-4 py-2.5 rounded-lg
                {{ request()->routeIs('seller.products.*') 
                        ? 'bg-gray-100 font-semibold text-blue-600 border-l-4 border-blue-600 -ml-1 pl-5' 
                        : 'hover:bg-gray-50 text-gray-700' }}">
                    <span>Manajemen Produk</span>
                </a>

                {{-- PESANAN --}}
                <a href="{{ route('seller.orders.index') }}"
                class="flex items-center gap-2 px-4 py-2.5 rounded-lg
                {{ request()->routeIs('seller.orders.*') 
                        ? 'bg-gray-100 font-semibold text-blue-600 border-l-4 border-blue-600 -ml-1 pl-5' 
                        : 'hover:bg-gray-50 text-gray-700' }}">
                    <span>Manajemen Pesanan</span>
                </a>

                {{-- PROFIL --}}
                <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-2 px-4 py-2.5 rounded-lg
                {{ request()->routeIs('profile.edit') 
                        ? 'bg-gray-100 font-semibold text-blue-600 border-l-4 border-blue-600 -ml-1 pl-5' 
                        : 'hover:bg-gray-50 text-gray-700' }}">
                    <span>Profil</span>
                </a>

            </nav>
        </div>

        {{-- LOGOUT --}}
        <form method="POST" action="{{ route('logout') }}" class="mt-8">
            @csrf
            <button
                class="w-full flex items-center gap-2 px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-lg">
                <span>Logout</span>
            </button>
        </form>

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
