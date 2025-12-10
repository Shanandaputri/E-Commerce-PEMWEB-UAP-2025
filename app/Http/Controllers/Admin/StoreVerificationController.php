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
        $pendingStores = Store::with('owner') // asumsi relasi owner() -> user
            ->where('is_verified', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.verification', compact('pendingStores'));
    }

    // Approve / verifikasi toko
    public function approve(Store $store)
    {
        $store->is_verified = true;
        $store->save();

        return back()->with('success', "Toko {$store->name} berhasil diverifikasi.");
    }

    // Reject toko (simple: hapus atau tandai ditolak)
    public function reject(Store $store)
    {
        // Versi simpel: hapus toko
        // $store->delete();

        // Versi aman: tambahkan kolom status kalau ada (misal status = 'rejected')
        $store->is_verified = false;
        $store->status = 'rejected'; // kalau kolomnya ada
        $store->save();

        return back()->with('success', "Toko {$store->name} ditolak.");
    }
}
