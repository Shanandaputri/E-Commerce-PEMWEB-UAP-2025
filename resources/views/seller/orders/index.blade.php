@extends('seller.layouts.app')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')

@section('content')
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Pesanan Terbaru</h2>
        </div>

        <table class="w-full text-sm border border-gray-100 rounded-lg overflow-hidden">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left border-b">Order</th>
                <th class="px-4 py-2 text-left border-b">Pelanggan</th>
                <th class="px-4 py-2 text-left border-b">Tanggal</th>
                <th class="px-4 py-2 text-left border-b">Status Pembayaran</th>
                <th class="px-4 py-2 text-right border-b">Total</th>
                <th class="px-4 py-2 text-left border-b">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                @php
                    $status = $order->payment_status ?? 'pending';
                    $badgeClass = match($status) {
                        'paid'      => 'bg-green-50 text-green-600',
                        'cancelled' => 'bg-red-50 text-red-600',
                        default     => 'bg-yellow-50 text-yellow-700',
                    };
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border-b font-medium">
                        #{{ $order->code ?? $order->id }}
                    </td>
                    <td class="px-4 py-2 border-b">
                        {{ $order->buyer->name ?? '-' }}
                    </td>
                    <td class="px-4 py-2 border-b">
                        {{ $order->created_at->format('d M Y') }}
                    </td>
                    <td class="px-4 py-2 border-b">
                        <span class="inline-flex items-center px-2 py-1 text-xs rounded-full {{ $badgeClass }}">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border-b text-right font-medium">
                        Rp {{ number_format($order->grand_total ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-2 border-b">
                        <a href="{{ route('seller.orders.show', $order) }}"
                           class="text-blue-600 hover:underline text-sm">
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                        Belum ada pesanan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
@endsection