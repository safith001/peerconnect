<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{
    public function store(User $user)
    {
        $me = Auth::id();

        if ($me === $user->id) {
            return back()->with('error', 'You cannot block yourself.');
        }

        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot block an admin.');
        }

        if (Block::where('blocker_id', $me)->where('blocked_id', $user->id)->exists()) {
            return back()->with('error', 'User is already blocked.');
        }

        Block::create([
            'blocker_id' => $me,
            'blocked_id' => $user->id,
        ]);

        return redirect()->route('conversations.index')->with('success', 'User has been blocked.');
    }

    public function destroy(User $user)
    {
        $me = Auth::id();

        Block::where('blocker_id', $me)->where('blocked_id', $user->id)->delete();

        return redirect()->route('conversations.index')->with('success', 'User has been unblocked.');
    }
}
