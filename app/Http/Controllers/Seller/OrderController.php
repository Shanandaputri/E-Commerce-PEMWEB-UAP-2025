<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;

        $orders = Transaction::with(['buyer', 'transactionDetails.product'])
            ->where('store_id', $store->id)
            ->latest()
            ->paginate(10);

        return view('seller.orders.index', compact('orders'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['buyer', 'transactionDetails.product']);

        $this->authorizeOrder($transaction);

        return view('seller.orders.show', [
            'order' => $transaction,
        ]);
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $this->authorizeOrder($transaction);

        $data = $request->validate([
            'payment_status' => ['required', 'in:pending,paid,cancelled'],
        ]);

        $transaction->update([
            'payment_status' => $data['payment_status'],
        ]);

        return redirect()
            ->route('seller.orders.show', $transaction)
            ->with('success', 'Status pembayaran pesanan berhasil diperbarui.');
    }

    protected function authorizeOrder(Transaction $transaction)
    {
        if ($transaction->store_id !== auth()->user()->store->id) {
            abort(403, 'Anda tidak berhak mengakses pesanan ini.');
        }
    }
}
