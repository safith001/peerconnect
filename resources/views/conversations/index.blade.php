@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center shadow">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Messages</h1>
    </div>

    @forelse($conversations as $c)
        @php
            $other = $c->otherParticipant($me);
            $last = $lastByConversation[$c->id] ?? null;
        @endphp
        <a href="{{ route('conversations.show', $c) }}"
           class="block bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-5 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-lg transition-all mb-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm">
                        {{ strtoupper(substr($other?->name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900 dark:text-white">{{ $other?->name ?? 'Unknown' }}</div>
                        @if($last)
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $last->sender_id === $me ? 'You: ' : '' }}{{ \Illuminate\Support\Str::limit($last->body, 70) }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col items-end gap-1">
                    @if($c->unread_count > 0)
                        <span class="inline-flex items-center justify-center text-xs font-bold bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full h-6 min-w-6 px-2 shadow">
                            {{ $c->unread_count }}
                        </span>
                    @endif
                    @if($last)
                        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $last->created_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>
        </a>
    @empty
        <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-12 text-center border border-gray-200/50 dark:border-gray-700/50">
            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <p class="text-gray-500 dark:text-gray-400 font-medium">No conversations yet.</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Find peers to start chatting!</p>
        </div>
    @endforelse
</div>
@endsection
