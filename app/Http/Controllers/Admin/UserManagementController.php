<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    // List semua user + store-nya
    public function index()
    {
        $users = User::with('store')   // asumsi relasi store() -> hasOne Store
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users', compact('users'));
    }

    // Update role user (admin / member)
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,member',
        ]);

        $user->role = $request->role;
        $user->save();

        return back()->with('success', "Role {$user->name} diubah menjadi {$user->role}.");
    }

    // Optional: hapus user
    public function destroy(User $user)
    {
        // hati-hati kalau ada relasi, tapi untuk tugas bisa cukup begini
        $user->delete();

        return back()->with('success', "User {$user->name} berhasil dihapus.");
    }
}
