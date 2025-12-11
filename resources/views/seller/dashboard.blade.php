@extends('seller.layouts.app')

@section('title', 'Dashboard Seller')
@section('page-title', 'Dashboard Seller')

@section('content')

    {{-- row cards statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white border border-gray-100 rounded-2xl px-5 py-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500 mb-1">Total Produk</p>
            <p class="text-2xl font-semibold text-gray-900">24</p>
            <p class="text-xs text-green-600 mt-1">+3 produk minggu ini</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl px-5 py-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500 mb-1">Pesanan Hari Ini</p>
            <p class="text-2xl font-semibold text-gray-900">5</p>
            <p class="text-xs text-gray-500 mt-1">Menunggu dikirim: 2</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl px-5 py-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500 mb-1">Pendapatan Bulan Ini</p>
            <p class="text-2xl font-semibold text-gray-900">Rp 12.500.000</p>
            <p class="text-xs text-green-600 mt-1">+18% dari bulan lalu</p>
        </div>
    </div>

    {{-- pesanan terbaru --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-800">
                Pesanan Terbaru
            </h2>
            <a href="#" class="text-xs text-blue-600 hover:underline">
                Lihat semua
            </a>
        </div>

        <div class="px-6 py-4">
            <table class="w-full text-sm">
                <thead>
                <tr class="text-xs uppercase text-gray-400">
                    <th class="py-2 text-left">Order</th>
                    <th class="py-2 text-left">Produk</th>
                    <th class="py-2 text-left">Tanggal</th>
                    <th class="py-2 text-left">Status</th>
                    <th class="py-2 text-right">Total</th>
                </tr>
                </thead>
                <tbody>
                {{-- dummy --}}
                <tr class="border-t border-gray-100">
                    <td class="py-3">#ORD-0012</td>
                    <td class="py-3">JEANS ZW WIDE LEG TIRO MD</td>
                    <td class="py-3">08 Des 2025</td>
                    <td class="py-3">
                        <span class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-green-50 text-green-600">
                            Selesai
                        </span>
                    </td>
                    <td class="py-3 text-right">Rp 1.699.000</td>
                </tr>
                <tr class="border-t border-gray-100">
                    <td class="py-3">#ORD-0011</td>
                    <td class="py-3">JAKET BOMBER WOL ZW</td>
                    <td class="py-3">08 Des 2025</td>
                    <td class="py-3">
                        <span class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-yellow-50 text-yellow-600">
                            Diproses
                        </span>
                    </td>
                    <td class="py-3 text-right">Rp 2.299.000</td>
                </tr>
                <tr class="border-t border-gray-100">
                    <td class="py-3">#ORD-0010</td>
                    <td class="py-3">T-Shirt Graphic Oversize</td>
                    <td class="py-3">07 Des 2025</td>
                    <td class="py-3">
                        <span class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-red-50 text-red-600">
                            Dibatalkan
                        </span>
                    </td>
                    <td class="py-3 text-right">Rp 399.000</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection