<?php

namespace App\Http\Controllers;

use App\Models\PeerRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeerRequestController extends Controller
{
    // Send a peer request
    public function send(User $user)
    {
        $me = Auth::id();

        if ($me === $user->id) {
            return back()->with('error', 'You cannot send request to yourself');
        }

        if (Auth::user()->isBlockedFrom($user)) {
            return back()->with('error', 'Unable to send request.');
        }

        // Check if already exists
        $exists = PeerRequest::where(function ($q) use ($me, $user) {
            $q->where('sender_id', $me)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($me, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $me);
        })->first();

        if ($exists) {
            return back()->with('error', 'Request already exists');
        }

        PeerRequest::create([
            'sender_id' => $me,
            'receiver_id' => $user->id,
        ]);

        return back()->with('success', 'Peer request sent');
    }

    // Accept a request
    public function accept(PeerRequest $request)
    {
        if ($request->receiver_id !== Auth::id()) {
            abort(403);
        }

        $request->update(['status' => 'accepted']);
        return back()->with('success', 'Peer request accepted');
    }

    // Decline a request
    public function decline(PeerRequest $request)
    {
        if ($request->receiver_id !== Auth::id()) {
            abort(403);
        }

        $request->update(['status' => 'declined']);
        return back()->with('success', 'Peer request declined');
    }

    // Unfriend a peer (delete the accepted request)
    public function unfriend(User $user)
    {
        $me = Auth::id();

        $peerRequest = PeerRequest::where('status', 'accepted')
            ->where(function ($q) use ($me, $user) {
                $q->where('sender_id', $me)->where('receiver_id', $user->id)
                  ->orWhere(function ($q) use ($me, $user) {
                      $q->where('sender_id', $user->id)->where('receiver_id', $me);
                  });
            })
            ->first();

        if (! $peerRequest) {
            return back()->with('error', 'You are not connected with this user.');
        }

        $peerRequest->delete();

        return back()->with('success', 'Unfriended successfully.');
    }

    // Show my requests
    public function index()
    {
        $userId = Auth::id();

        $incoming = PeerRequest::where('receiver_id', $userId)
            ->where('status', 'pending')
            ->with('sender')
            ->get();

        $sent = PeerRequest::where('sender_id', $userId)
            ->with('receiver')
            ->get();

        return view('peer_requests.index', compact('incoming', 'sent'));
    }
}
