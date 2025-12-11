<x-app-layout>
    <div class="bg-gray-100 min-h-screen">
        <div class="flex">

            {{-- SIDEBAR --}}
            <aside class="w-64 bg-white border-r flex flex-col justify-between fixed inset-y-0">

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

                    <nav class="mt-4 px-3 space-y-1 text-sm">
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-3 px-3 py-3 rounded-xl
                           {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('admin.verification.index') }}"
                           class="flex items-center gap-3 px-3 py-3 rounded-xl
                           {{ request()->routeIs('admin.verification.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M9 12l2 2 4-4M5 7h14M5 17h14M5 12h.01M19 12h.01"/>
                            </svg>
                            <span>Verifikasi Toko</span>
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-3 px-3 py-3 rounded-xl
                           {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M17 20h5v-2a4 4 0 00-5-3.87M9 11a4 4 0 100-8 4 4 0 000 8zm8 1a4 4 0 10-4-4m-6 5a6 6 0 00-6 6v1h7"/>
                            </svg>
                            <span>Manajemen User &amp; Store</span>
                        </a>
                    </nav>
                </div>

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

                    <div class="mb-6">
                        <h1 class="text-2xl font-semibold text-gray-900">Verifikasi Toko</h1>
                        <p class="text-sm text-gray-500 mt-1">
                            Daftar toko yang menunggu verifikasi admin.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                        <h3 class="text-lg font-semibold mb-4">Toko Menunggu Verifikasi</h3>

                        @if ($pendingStores->isEmpty())
                            <p class="text-gray-500 text-sm">
                                Belum ada toko yang menunggu verifikasi.
                            </p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="border-b bg-gray-50">
                                            <th class="px-3 py-2 text-left">Nama Toko</th>
                                            <th class="px-3 py-2 text-left">Pemilik</th>
                                            <th class="px-3 py-2 text-left">Tanggal</th>
                                            <th class="px-3 py-2 text-left">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingStores as $store)
                                            <tr class="border-b">
                                                <td class="px-3 py-2">{{ $store->name }}</td>
                                                <td class="px-3 py-2">{{ $store->owner->name }}</td>
                                                <td class="px-3 py-2">{{ $store->created_at->format('d M Y') }}</td>
                                                <td class="px-3 py-2 space-x-2">
                                                    <form method="POST"
                                                          action="{{ route('admin.verification.approve', $store) }}"
                                                          class="inline">
                                                        @csrf
                                                        <button class="px-3 py-1 text-sm rounded bg-green-600 text-white">
                                                            Approve
                                                        </button>
                                                    </form>

                                                    <form method="POST"
                                                          action="{{ route('admin.verification.reject', $store) }}"
                                                          class="inline">
                                                        @csrf
                                                        <button class="px-3 py-1 text-sm rounded bg-red-600 text-white">
                                                            Reject
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    </div>

                </div>
            </main>

        </div>
    </div>
</x-app-layout>
