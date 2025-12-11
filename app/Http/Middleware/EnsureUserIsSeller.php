<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSeller
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // harus role member
        if ($user->role !== 'member') {
            abort(403, 'Hanya member yang boleh menjadi seller.');
        }

        // harus punya store
        if (!$user->store) {
            return redirect()->route('store.register')
                ->with('error', 'Kamu belum punya toko, daftar toko dulu.');
        }

        // store harus sudah diverifikasi admin
        if (!$user->store->is_verified) {
            return redirect()->route('customer.dashboard')
                ->with('error', 'Toko kamu belum diverifikasi admin.');
        }

        return $next($request);
    }

}
