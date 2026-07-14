@extends('layouts.app')

@section('content')
@php
    $other = $conversation->otherParticipant($me);
@endphp

<div class="flex flex-col h-[80vh] max-w-3xl mx-auto bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-sm transition-colors">

    <!-- Header -->
    <div class="flex items-center justify-between px-4 py-3 border-b dark:border-gray-700">
        <h1 class="text-lg font-semibold text-gray-800 dark:text-white">
            {{ $other?->name ?? 'Conversation' }}
        </h1>
        <a href="{{ route('conversations.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">&larr; All messages</a>
    </div>

    <!-- Messages -->
    <div id="chat-box" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 dark:bg-gray-900 transition-colors">
        @foreach($conversation->messages as $m)
            <div class="flex {{ $m->sender_id === $me ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[70%] px-4 py-2 rounded-2xl shadow-sm
                    {{ $m->sender_id === $me ? 'bg-blue-600 text-white rounded-br-none' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-bl-none' }}">

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
          class="flex items-center gap-2 border-t dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800">
        @csrf
        <input name="body" class="flex-1 border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Write a message..." required>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full text-sm transition-colors">Send</button>
    </form>
@else
    <div class="p-4 text-center text-sm text-gray-500 dark:text-gray-400 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
        You can only chat with accepted peers.
    </div>
@endif

<!-- Auto-scroll -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let chatBox = document.getElementById("chat-box");
        chatBox.scrollTop = chatBox.scrollHeight;
    });
</script>
@endsection
