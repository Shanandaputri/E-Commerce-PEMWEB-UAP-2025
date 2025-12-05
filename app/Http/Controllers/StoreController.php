<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Tampilkan form register toko
     */
    public function create()
    {
        $user = auth()->user();

        // Kalau user sudah punya toko, jangan boleh daftar lagi
        if ($user->store) {
            return redirect()
                ->route('seller.dashboard') // nanti bisa diganti ke halaman seller
                ->with('status', 'Kamu sudah memiliki toko.');
        }

        return view('store.register');
    }

    /**
     * Simpan data toko baru
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Cegah user bikin toko lebih dari satu
        if ($user->store) {
            return redirect()
                ->route('seller.dashboard')
                ->with('status', 'Kamu sudah memiliki toko.');
        }

        // Validasi input
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'about'       => ['nullable', 'string'],
            'phone'       => ['required', 'string', 'max:50'],
            'city'        => ['required', 'string', 'max:100'],
            'address'     => ['required', 'string'],
            'postal_code' => ['required', 'string', 'max:20'],
            'logo'        => ['nullable', 'image', 'max:2048'], // 2MB
        ]);

        // Handle logo (kalau di-upload)
        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('store_logos', 'public');
        } else {
            // boleh pakai default image kalau mau
            $logoPath = 'default-store-logo.png';
        }

        // Simpan ke tabel stores
        Store::create([
            'user_id'     => $user->id,
            'name'        => $validated['name'],
            'logo'        => $logoPath,
            'about'       => $validated['about'] ?? null,
            'phone'       => $validated['phone'],
            'address_id'  => null, // belum dipakai, boleh null
            'city'        => $validated['city'],
            'address'     => $validated['address'],
            'postal_code' => $validated['postal_code'],
            'is_verified' => false, // default: belum diverifikasi admin
        ]);

        return redirect()
            ->route('seller.dashboard') // nanti ganti ke view seller beneran
            ->with('status', 'Toko berhasil didaftarkan, menunggu verifikasi admin.');
    }
}
