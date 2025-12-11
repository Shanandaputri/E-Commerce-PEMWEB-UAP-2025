<x-app-layout>
    <div class="bg-gray-100 min-h-screen">
        <div class="flex">

            {{-- SIDEBAR ADMIN --}}
            <aside class="w-64 bg-white border-r flex flex-col justify-between fixed inset-y-0">

                {{-- Header logo info admin --}}
                <div>
                    <div class="px-6 py-6 border-b">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold">
                                AD
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Admin Panel</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->name }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Menu --}}
                    <nav class="mt-4 px-3 space-y-1 text-sm">

                        {{-- Dashboard --}}
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-3 px-3 py-3 rounded-xl
                           {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        {{-- Verifikasi Toko --}}
                        <a href="{{ route('admin.verification.index') }}"
                           class="flex items-center gap-3 px-3 py-3 rounded-xl
                           {{ request()->routeIs('admin.verification.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M9 12l2 2 4-4M5 7h14M5 17h14M5 12h.01M19 12h.01"/>
                            </svg>
                            <span>Verifikasi Toko</span>
                        </a>

                        {{-- Manajemen User & Store --}}
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-3 px-3 py-3 rounded-xl
                           {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M17 20h5v-2a4 4 0 00-5-3.87M9 11a4 4 0 100-8 4 4 0 000 8zm8 1a4 4 0 10-4-4m-6 5a6 6 0 00-6 6v1h7"/>
                            </svg>
                            <span>Manajemen User & Store</span>
                        </a>
                    </nav>
                </div>

                {{-- Logout --}}
                <div class="px-5 py-5 border-t">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-2 text-sm font-medium text-red-500 hover:text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M15 17l5-5m0 0l-5-5m5 5H9m4 5v1a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h5a2 2 0 012 2v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>

            </aside>

            {{-- MAIN CONTENT --}}
            <main class="ml-64 flex-1 overflow-y-auto">
                <div class="max-w-6xl mx-auto px-8 py-8">

                    {{-- Header --}}
                    <div class="mb-6">
                        <h1 class="text-2xl font-semibold text-gray-900">Admin Dashboard</h1>
                        <p class="text-sm text-gray-500 mt-1">
                            Ringkasan singkat aktivitas sistem &amp; menu admin.
                        </p>
                    </div>

                    {{-- Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        {{-- TOTAL USER --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total User</p>
                            <p class="mt-3 text-2xl font-semibold text-gray-900">
                                {{ $totalUsers }}
                            </p>
                            <p class="mt-1 text-xs text-gray-400">Termasuk admin, member, dan seller.</p>
                        </div>

                        {{-- TOKO MENUNGGU VERIFIKASI --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Toko Menunggu Verifikasi</p>
                            <p class="mt-3 text-2xl font-semibold text-gray-900">
                                {{ $pendingStores }}
                            </p>
                            <p class="mt-1 text-xs text-gray-400">Cek di menu Verifikasi Toko.</p>
                        </div>

                        {{-- TRANSAKSI HARI INI --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Transaksi Hari Ini</p>
                            <p class="mt-3 text-2xl font-semibold text-gray-900">
                                {{ $todayTransactions }}
                            </p>
                            <p class="mt-1 text-xs text-gray-400">Data diambil dari tabel transactions.</p>
                        </div>
                    </div>

                    {{-- Welcome box --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <p class="text-gray-800 text-sm">
                            Halo, <span class="font-semibold">{{ auth()->user()->name }}</span>! 👋
                        </p>
                        <p class="mt-2 text-sm text-gray-600">
                            Ini adalah panel <strong>Admin</strong>. Dari sini kamu bisa:
                        </p>
                        <ul class="mt-3 text-sm text-gray-600 list-disc list-inside space-y-1">
                            <li>Melihat &amp; memverifikasi pendaftaran toko baru.</li>
                            <li>Mengelola role dan data semua user &amp; store.</li>
                            <li>(Opsional) menambah fitur monitoring transaksi, withdraw, dan lain-lain.</li>
                        </ul>

                        <div class="mt-4 flex flex-wrap gap-3 text-sm">
                            <a href="{{ route('admin.verification.index') }}"
                               class="inline-flex items-center px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                                Verifikasi Toko
                            </a>
                            <a href="{{ route('admin.users.index') }}"
                               class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">
                                Manajemen User &amp; Store
                            </a>
                        </div>
                    </div>

                </div>
            </main>

        </div>
    </div>
</x-app-layout>
