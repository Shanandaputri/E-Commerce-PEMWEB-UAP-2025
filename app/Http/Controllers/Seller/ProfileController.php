<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $store = $user->store ?? null;

        return view('seller.profile.index', compact('user', 'store'));
    }

    public function edit()
    {
        $user = auth()->user();
        $store = $user->store ?? null;

        return view('seller.profile.edit', compact('user', 'store'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:30',
            'store_name' => 'nullable|string|max:255',
        ]);

        // update user
        $user->update([
            'name' => $data['name'],
            'phone_number' => $data['phone_number'] ?? null,
        ]);

        // update store
        if ($user->store && isset($data['store_name'])) {
            $user->store->update([
                'name' => $data['store_name'],
            ]);
        }

        // ⬅️ INI KUNCINYA
        return redirect()
            ->route('seller.profile.index')
            ->with('success', 'Profil berhasil diperbarui');
    }

}
