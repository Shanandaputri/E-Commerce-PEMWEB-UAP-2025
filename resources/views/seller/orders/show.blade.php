@extends('seller.layouts.app')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')

@section('content')
    <div class="max-w-5xl space-y-6">

        <a href="{{ route('seller.orders.index') }}"
           class="text-sm text-gray-500 hover:text-gray-800 inline-flex items-center">
            ← Kembali ke daftar pesanan
        </a>

        {{-- HEADER --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Order</p>
                <p class="text-xl font-semibold">
                    #{{ $order->code ?? $order->id }}
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    Tanggal: {{ $order->created_at->format('d M Y H:i') }}
                </p>
            </div>

            <div class="text-right">
                @php
                    $status = $order->payment_status ?? 'pending';
                    $badgeClass = match($status) {
                        'paid'      => 'bg-green-50 text-green-600',
                        'cancelled' => 'bg-red-50 text-red-600',
                        default     => 'bg-yellow-50 text-yellow-700',
                    };
                @endphp

                <span class="inline-flex items-center px-3 py-1.5 text-xs rounded-full {{ $badgeClass }}">
                    {{ ucfirst($status) }}
                </span>

                {{-- Form update status pembayaran --}}
                <form action="{{ route('seller.orders.updateStatus', $order) }}" method="POST" class="mt-2">
                    @csrf
                    @method('PATCH')
                    <select name="payment_status"
                            class="text-xs border rounded-lg px-2 py-1 focus:outline-none focus:ring-1 focus:ring-black/60">
                        <option value="pending"   @selected($status === 'pending')>Pending</option>
                        <option value="paid"      @selected($status === 'paid')>Paid</option>
                        <option value="cancelled" @selected($status === 'cancelled')>Cancelled</option>
                    </select>
                    <button type="submit"
                            class="ml-1 inline-flex items-center px-3 py-1.5 rounded-lg bg-black text-white text-xs font-medium hover:bg-gray-800">
                        Update
                    </button>
                </form>
            </div>
        </div>

        {{-- INFO PELANGGAN & ALAMAT --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5">
                <h3 class="text-sm font-semibold mb-3">Pelanggan</h3>
                <p class="text-sm font-medium">
                    {{ $order->buyer->name ?? '-' }}
                </p>
                <p class="text-xs text-gray-500">
                    {{ $order->buyer->email ?? '-' }}
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5">
                <h3 class="text-sm font-semibold mb-3">Alamat Pengiriman</h3>
                <p class="text-sm font-medium">{{ $order->recipient_name ?? '-' }}</p>
                <p class="text-sm text-gray-700">
                    {{ $order->address ?? '-' }}
                </p>
                <p class="text-sm text-gray-700">
                    {{ $order->city ?? '' }} {{ $order->postal_code ?? '' }}
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    Pengiriman: {{ $order->shipping_type ?? $order->shipping ?? '-' }}
                </p>
                <p class="text-xs text-gray-500">
                    Ongkir: Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    Metode Pembayaran: {{ strtoupper($order->payment_method ?? '-') }}
                </p>
                @if($order->va_number)
                    <p class="text-xs text-gray-500">
                        VA: {{ $order->va_number }}
                    </p>
                @endif
            </div>
        </div>

        {{-- ITEM PESANAN --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
            <h3 class="text-sm font-semibold mb-4">Item Pesanan</h3>

            <table class="w-full text-sm">
                <thead class="border-b">
                <tr>
                    <th class="py-2 text-left">Produk</th>
                    <th class="py-2 text-right">Qty</th>
                    <th class="py-2 text-right">Harga</th>
                    <th class="py-2 text-right">Subtotal</th>
                </tr>
                </thead>
                <tbody>
                @php $grandTotal = 0; @endphp

                @forelse($order->transactionDetails as $item)
                    @php
                        $qty   = $item->qty ?? 1;
                        $sub   = $item->subtotal ?? 0;
                        $price = $qty > 0 ? $sub / $qty : $sub;
                        $grandTotal += $sub;
                    @endphp
                    <tr class="border-b last:border-0">
                        <td class="py-2">
                            {{ $item->product->name ?? 'Produk dihapus' }}
                        </td>
                        <td class="py-2 text-right">
                            {{ $qty }}
                        </td>
                        <td class="py-2 text-right">
                            Rp {{ number_format($price, 0, ',', '.') }}
                        </td>
                        <td class="py-2 text-right">
                            Rp {{ number_format($sub, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">
                            Belum ada detail item.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="mt-4 flex justify-end">
                <div class="text-right">
                    <p class="text-sm text-gray-500">Grand Total</p>
                    <p class="text-xl font-semibold">
                        Rp {{ number_format($order->grand_total ?? $grandTotal, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

    </div>
@endsection
