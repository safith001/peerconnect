@extends('layouts.app')

@section('content')
@php
    $other = $conversation->otherParticipant($me);
@endphp

<div class="flex flex-col h-[80vh] max-w-3xl mx-auto bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm border border-gray-200/50 dark:border-gray-700/50 rounded-2xl shadow-lg transition-all overflow-hidden">

    <!-- Header -->
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200/50 dark:border-gray-700/50">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                {{ strtoupper(substr($other?->name ?? '?', 0, 1)) }}
            </div>
            <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $other?->name ?? 'Conversation' }}</h1>
        </div>
        <div class="flex items-center gap-2">
            @if($other && auth()->user()->hasBlocked($other))
                <form method="POST" action="{{ route('unblock', $other) }}" class="inline">
                    @csrf
                    <button class="text-xs px-3 py-1.5 bg-orange-100/50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 border border-orange-200/50 dark:border-orange-800/50 font-medium transition-all">Unblock</button>
                </form>
            @elseif($other)
                <form method="POST" action="{{ route('block', $other) }}"
                      onsubmit="return confirm('Block {{ $other->name }}?')" class="inline">
                    @csrf
                    <button class="text-xs px-3 py-1.5 bg-gray-100/50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600/50 border border-gray-200/50 dark:border-gray-700/50 font-medium transition-all">Block</button>
                </form>
            @endif
            <a href="{{ route('conversations.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">&larr; All messages</a>
        </div>
    </div>

    <!-- Messages -->
    <div id="chat-box" class="flex-1 overflow-y-auto p-5 space-y-3 bg-gray-50/30 dark:bg-gray-900/30 transition-colors">
        @foreach($conversation->messages as $m)
            <div class="flex {{ $m->sender_id === $me ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[70%] px-4 py-2.5 shadow-sm
                    {{ $m->sender_id === $me
                        ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl rounded-br-none'
                        : 'bg-white/80 dark:bg-gray-700/80 text-gray-900 dark:text-gray-100 rounded-2xl rounded-bl-none border border-gray-200/50 dark:border-gray-700/50' }}">

                    <p class="text-sm leading-relaxed">{{ $m->body }}</p>

                    <span class="block text-[11px] mt-1 opacity-70">
                        {{ $m->created_at->format('M d, H:i') }}
                        @if($m->sender_id === $me && $m->id === $lastSeenMyMsgId)
                            &bull; Seen
                        @endif
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Input (only if peers) -->
    @if(auth()->user()->isPeerWith($conversation->otherParticipant($me)))
        <form action="{{ route('conversations.send', $conversation) }}" method="POST"
              class="flex items-center gap-2 border-t border-gray-200/50 dark:border-gray-700/50 px-5 py-4 bg-white/50 dark:bg-gray-800/50">
            @csrf
            <input name="body" class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-gray-200 rounded-full px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Write a message..." required>
            <button class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-5 py-2.5 rounded-full text-sm font-semibold shadow-lg shadow-blue-600/20 transition-all">Send</button>
        </form>
    @else
        <div class="p-4 text-center text-sm text-gray-500 dark:text-gray-400 border-t border-gray-200/50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50">
            You can only chat with accepted peers.
        </div>
    @endif
</div>

<!-- Auto-scroll -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let chatBox = document.getElementById("chat-box");
        chatBox.scrollTop = chatBox.scrollHeight;
    });
</script>
@endsection
