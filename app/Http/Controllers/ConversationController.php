<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    // List my conversations with unread counts and last message preview
    public function index()
    {
        $me = Auth::id();

        $conversations = Conversation::involves($me)
            ->with(['userOne','userTwo'])
            ->withCount([
                // unread messages FROM the other person TO me
                'messages as unread_count' => function ($q) use ($me) {
                    $q->whereNull('read_at')->where('sender_id', '!=', $me);
                }
            ])
            ->latest('updated_at')
            ->get();

        // Load latest message for preview
        $lastByConversation = Message::select('id','conversation_id','sender_id','body','created_at')
            ->whereIn('conversation_id', $conversations->pluck('id'))
            ->orderByDesc('id')
            ->get()
            ->groupBy('conversation_id')
            ->map->first();

        return view('conversations.index', [
            'conversations' => $conversations,
            'lastByConversation' => $lastByConversation,
            'me' => $me,
        ]);
    }

    // Start (or open) a conversation with a user
    // Start (or open) a conversation with a user
public function start(User $user)
{
    $me = Auth::user();
    abort_if($user->id === $me->id, 403);

    // ✅ Only allow if they are accepted peers
    if (!$me->isPeerWith($user)) {
        return back()->with('error', 'You can only start a conversation with accepted peers.');
    }

    if ($me->isBlockedFrom($user)) {
        return redirect()->route('conversations.index')->with('error', 'Unable to start conversation.');
    }

    [$a, $b] = $me->id < $user->id ? [$me->id, $user->id] : [$user->id, $me->id];

    $conversation = Conversation::firstOrCreate([
        'user_one_id' => $a,
        'user_two_id' => $b,
    ]);

    return redirect()->route('conversations.show', $conversation);
}

// Show messages; mark other's unread as read; compute last "Seen"
public function show(Conversation $conversation)
{
    $me = Auth::user();
    abort_unless(($conversation->user_one_id === $me->id) || ($conversation->user_two_id === $me->id), 403);

    $other = $conversation->otherParticipant($me->id);

    // ✅ Only allow viewing if they are accepted peers
    if (!$me->isPeerWith($other)) {
        return redirect()->route('conversations.index')->with('error', 'You can only chat with accepted peers.');
    }

    if ($me->isBlockedFrom($other)) {
        return redirect()->route('conversations.index')->with('error', 'This conversation is not available.');
    }

    // Mark all messages FROM the other person TO me as read now
    Message::where('conversation_id', $conversation->id)
        ->where('sender_id', '!=', $me->id)
        ->whereNull('read_at')
        ->update(['read_at' => now()]);

    $conversation->load(['messages.sender','userOne','userTwo']);

    // Your latest message that HAS been read by the other person (for "Seen")
    $lastSeenMyMsg = Message::where('conversation_id', $conversation->id)
        ->where('sender_id', $me->id)
        ->whereNotNull('read_at')
        ->orderByDesc('id')
        ->first();

    return view('conversations.show', [
        'conversation' => $conversation,
        'me' => $me->id,
        'lastSeenMyMsgId' => $lastSeenMyMsg?->id,
    ]);
}

// Send a new message
public function send(Request $request, Conversation $conversation)
{
    $me = Auth::user();
    abort_unless(($conversation->user_one_id === $me->id) || ($conversation->user_two_id === $me->id), 403);

    $other = $conversation->otherParticipant($me->id);

    // ✅ Only allow sending if they are accepted peers
    if (!$me->isPeerWith($other)) {
        return back()->with('error', 'You can only message accepted peers.');
    }

    if ($me->isBlockedFrom($other)) {
        return back()->with('error', 'Unable to send message.');
    }

    $request->validate([
        'body' => ['required','string','max:5000']
    ]);

    $conversation->messages()->create([
        'sender_id' => $me->id,
        'body'      => $request->body,
    ]);

    $conversation->touch();

    return back();
}

}
