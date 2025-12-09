<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        // Ambil semua transaksi user yang login
        $transactions = Transaction::with(['details.product.productImages'])
            ->where('buyer_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        // Transaksi yang sudah dibayar
        $completed = $transactions->where('payment_status', 'paid');

        // Transaksi yang dibatalkan (kalau kamu pakai status 'canceled' atau 'failed', tinggal sesuaikan)
        $canceled = $transactions->where('payment_status', 'canceled');

        return view('customer.history.index', [
            'transactions' => $transactions,
            'completed'    => $completed,
            'canceled'     => $canceled,
        ]);
    }
}
