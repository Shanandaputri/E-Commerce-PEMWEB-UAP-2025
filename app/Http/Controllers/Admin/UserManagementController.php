<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    // List semua user dan storenya
    public function index()
    {
        $users = User::with('store')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users', compact('users'));
    }

    // Update role user
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,member',
        ]);

        $user->role = $request->role;
        $user->save();

        return back()->with('success', "Role {$user->name} diubah menjadi {$user->role}.");
    }

    // hapus user
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', "User {$user->name} berhasil dihapus.");
    }
}
