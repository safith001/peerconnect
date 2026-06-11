@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Messages</h1>

    @forelse($conversations as $c)
        @php
            $other = $c->otherParticipant($me);
            $last = $lastByConversation[$c->id] ?? null;
        @endphp
        <a href="{{ route('conversations.show', $c) }}"
           class="block bg-white border rounded-xl p-4 mb-3 hover:bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="font-semibold">{{ $other?->name ?? 'Unknown' }}</div>

                @if($c->unread_count > 0)
                    <span class="inline-flex items-center justify-center text-xs font-bold bg-blue-600 text-white rounded-full h-6 min-w-6 px-2">
                        {{ $c->unread_count }}
                    </span>
                @endif
            </div>
            @if($last)
                <div class="text-sm text-gray-600 mt-1">
                    {{ $last->sender_id === $me ? 'You: ' : '' }}{{ \Illuminate\Support\Str::limit($last->body, 70) }}
                </div>
                <div class="text-xs text-gray-400 mt-0.5">
                    {{ $last->created_at->diffForHumans() }}
                </div>
            @endif
        </a>
    @empty
        <p class="text-gray-500">No conversations yet.</p>
    @endforelse
</div>
@endsection
