<?php

namespace Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->get('suspended') === '1') {
            $query->whereNotNull('suspended_at');
        }

        $users = $query->latest()->paginate(20);

        return view('admin::users.index', compact('users'));
    }

    public function suspend(User $user, Request $request)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot suspend another admin.');
        }

        $request->validate(['reason' => 'required|string|max:500']);

        $user->update([
            'suspended_at' => now(),
            'suspension_reason' => $request->reason,
        ]);

        return back()->with('success', "User '{$user->name}' has been suspended.");
    }

    public function unsuspend(User $user)
    {
        $user->update([
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);

        return back()->with('success', "User '{$user->name}' has been restored.");
    }
}
