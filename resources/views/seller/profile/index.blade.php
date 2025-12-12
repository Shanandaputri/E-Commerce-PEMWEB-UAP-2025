@extends('seller.layouts.app')

@section('title', 'Profil')
@section('page-title', 'Profil')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white border rounded-2xl p-5">
        <p class="text-xs text-gray-500">Nama</p>
        <p class="font-semibold">{{ $user->name }}</p>
        <p class="text-xs text-gray-500">{{ $user->email }}</p>
    </div>

    <div class="bg-white border rounded-2xl p-5">
        <p class="text-xs text-gray-500">Toko</p>
        <p class="font-semibold">{{ $store->name ?? '-' }}</p>
        <p class="text-xs text-gray-500">{{ $store->city ?? '-' }}</p>
    </div>

    <div class="bg-white border rounded-2xl p-5">
        <p class="text-xs text-gray-500">Status Verifikasi</p>
        @if(optional($store)->is_verified)
            <span class="text-green-600 text-sm">Terverifikasi</span>
        @else
            <span class="text-yellow-600 text-sm">Menunggu verifikasi</span>
        @endif
    </div>
</div>

<a href="{{ route('seller.profile.edit') }}"
   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg">
    Edit Profil
</a>

@endsection
