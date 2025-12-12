@extends('seller.layouts.app')

@section('title', 'Profil')
@section('page-title', 'Profil')

@section('content')

    {{-- ringkasan kecil seperti dashboard --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white border border-gray-100 rounded-2xl px-5 py-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500 mb-1">Nama</p>
            <p class="text-base font-semibold text-gray-900">{{ $user->name }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $user->email }}</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl px-5 py-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500 mb-1">Toko</p>
            <p class="text-base font-semibold text-gray-900">{{ optional($store)->name ?? '-' }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ optional($store)->city ?? '-' }}</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl px-5 py-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500 mb-1">Status Verifikasi</p>

            @if(optional($store)->is_verified == 1)
                <span class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-green-50 text-green-600">
                    Terverifikasi
                </span>
            @else
                <span class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-yellow-50 text-yellow-700">
                    Menunggu Verifikasi
                </span>
            @endif
        </div>
    </div>

    {{-- box form --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
        <h2 class="text-lg font-semibold mb-4">Edit Profil</h2>

        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 text-red-700 border border-red-100 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('seller.profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- DATA AKUN --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Data Akun</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Nama</label>
                        <input
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            class="mt-1 w-full rounded-lg border-gray-200"
                        />
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">No. HP</label>
                        <input
                            name="phone_number"
                            value="{{ old('phone_number', $user->phone_number) }}"
                            class="mt-1 w-full rounded-lg border-gray-200"
                        />
                    </div>
                </div>
            </div>

            <hr>

            {{-- DATA TOKO --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Data Toko</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="text-sm text-gray-600">Nama Toko</label>
                        <input
                            name="store_name"
                            value="{{ old('store_name', optional($store)->name) }}"
                            class="mt-1 w-full rounded-lg border-gray-200"
                        />
                    </div>
                </div>
            </div>

            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </form>
    </div>

@endsection
