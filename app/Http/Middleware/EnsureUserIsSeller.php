<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSeller
{
    public function handle(Request $request, Closure $next): Response
    {
        // harus login dulu
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // harus role member
        if ($user->role !== 'member') {
            abort(403, 'Hanya member yang boleh jadi seller.');
        }

        // harus punya store
        if (!$user->store) {
            // nanti bisa diarahkan ke halaman register store
            return redirect()->route('store.register')
                ->with('error', 'Kamu belum punya toko, daftar toko dulu.');
        }

        return $next($request);
    }
}
