<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreVerificationController extends Controller
{
    // List toko yang belum terverifikasi
    public function index()
    {
        $pendingStores = Store::with('owner')
            ->where('is_verified', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.verification', compact('pendingStores'));
    }

    // verifikasi toko
    public function approve(Store $store)
    {
        $store->is_verified = true;
        $store->save();

        return back()->with('success', "Toko {$store->name} berhasil diverifikasi.");
    }

    // Reject toko
    public function reject(Store $store)
    {
        $store->is_verified = false;
        $store->status = 'rejected';
        $store->save();

        return back()->with('success', "Toko {$store->name} ditolak.");
    }
}
