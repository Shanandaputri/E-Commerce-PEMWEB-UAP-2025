<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        // Transaksi SELESAI
        $completed = Transaction::with(['details.product'])
            ->where('buyer_id', Auth::id())               // kolom di tabel: buyer_id
            ->where('payment_status', 'completed')        // sesuaikan dengan nilai di DB
            ->latest()
            ->get();

        // Transaksi DIBATALKAN
        $canceled = Transaction::with(['details.product'])
            ->where('buyer_id', Auth::id())
            ->where('payment_status', 'canceled')         // sesuaikan juga
            ->latest()
            ->get();

        return view('customer.history.index', compact('completed', 'canceled'));
    }
}
