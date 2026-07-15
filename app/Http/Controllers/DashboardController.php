<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Message;
use App\Models\User;
use App\Models\PeerRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 🔹 Stats
        $totalPosts = Post::count();

        $connections = PeerRequest::where('status', 'accepted')
            ->where(function($q) use ($user) {
                $q->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id);
            })
            ->count();

        $messagesCount = Message::count();

        $unreadMessages = Message::whereNull('read_at')
    ->where('sender_id', '!=', $user->id)   // ✅ correct column
    ->whereHas('conversation', function($q) use ($user) {
        $q->where('user_one_id', $user->id)
          ->orWhere('user_two_id', $user->id);
    })
    ->count();

        $profileViews = 42; // dummy value
        $profileCompletion = 70; // dummy value

        // 🔹 Data
        $recentPosts = Post::latest()->take(5)->get();

        // ✅ Important: load sender relationship for proper display
        $recentMessages = Message::with('sender')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalPosts',
            'connections',
            'messagesCount',
            'unreadMessages',
            'profileViews',
            'profileCompletion',
            'recentPosts',
            'recentMessages'
        ));
    }
}
