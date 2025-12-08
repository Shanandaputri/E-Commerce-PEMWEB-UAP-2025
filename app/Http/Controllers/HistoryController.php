<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        // ambil semua transaksi user yang login
        $completed = Transaction::with(['details.product.productImages'])
            ->where('buyer_id', Auth::id())
            ->where('payment_status', 'paid')
            ->orderByDesc('created_at')
            ->get();

        $canceled = collect();

        return view('history', compact('completed', 'canceled'));
    }
}
