<?php

namespace App\View\Composers;

use App\Models\PeerRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PeerRequestComposer
{
    public function compose(View $view): void
    {
        $pendingRequests = collect();
        $pendingCount = 0;

        if (Auth::check()) {
            $pendingRequests = PeerRequest::where('receiver_id', Auth::id())
                ->where('status', 'pending')
                ->with('sender')
                ->latest()
                ->get();
            $pendingCount = $pendingRequests->count();
        }

        $view->with('pendingRequests', $pendingRequests)
             ->with('pendingCount', $pendingCount);
    }
}
