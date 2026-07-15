<?php

namespace Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use App\Models\PeerRequest;
use App\Models\Report;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'posts' => Post::count(),
            'comments' => Comment::count(),
            'reports_pending' => Report::where('status', 'pending')->count(),
            'peers_total' => PeerRequest::where('status', 'accepted')->count(),
            'peers_pending' => PeerRequest::where('status', 'pending')->count(),
            'users_suspended' => User::whereNotNull('suspended_at')->count(),
            'users_joined_today' => User::whereDate('created_at', today())->count(),
        ];

        $recent_users = User::latest()->take(5)->get();
        $recent_reports = Report::where('status', 'pending')
            ->with('reportable', 'reporter')
            ->latest()
            ->take(5)
            ->get();

        return view('admin::dashboard', compact('stats', 'recent_users', 'recent_reports'));
    }
}
