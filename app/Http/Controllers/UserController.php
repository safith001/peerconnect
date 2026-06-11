<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Public profile page
   public function show(User $user)
    {
        // recent posts for this user, newest first
        $user->loadCount('posts')
             ->load(['posts' => function ($q) {
                 $q->latest()->select('id','user_id','title','created_at');
             }]);

        return view('users.show', compact('user'));
    }

    // Find Peers list + filters
    public function index(Request $request)
    {
        $request->validate([
            'q'        => ['nullable','string','max:100'],
            'faculty'  => ['nullable','string','max:255'],
            'semester' => ['nullable','string','max:50'],
            'dept'     => ['nullable','string','max:255'],
        ]);

        $query = User::query()
            ->when($request->q, function ($q) use ($request) {
                $q->where(function ($w) use ($request) {
                    $w->where('name', 'like', '%'.$request->q.'%')
                      ->orWhere('email', 'like', '%'.$request->q.'%')
                      ->orWhere('student_id', 'like', '%'.$request->q.'%');
                });
            })
            ->when($request->faculty, fn($q) => $q->where('faculty', $request->faculty))
            ->when($request->semester, fn($q) => $q->where('semester', $request->semester))
            ->when($request->dept, fn($q) => $q->where('department', $request->dept))
            ->orderBy('name');

        if (Auth::check()) {
    $query->where('id', '!=', Auth::id());
}

        $users = $query->paginate(10)->withQueryString();

        $faculties   = User::select('faculty')->whereNotNull('faculty')->distinct()->pluck('faculty');
        $departments = User::select('department')->whereNotNull('department')->distinct()->pluck('department');

        return view('users.index', compact('users', 'faculties', 'departments'));
    }
}
