<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use App\Models\Transaction;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total semua user
        $totalUsers = User::count();

        // Toko yang belum diverifikasi
        $pendingStores = Store::where('is_verified', 0)->count();

        // Transaksi hari ini yang sudah dibayar
        $todayTransactions = Transaction::where('payment_status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'pendingStores',
            'todayTransactions'
        ));
    }
}
