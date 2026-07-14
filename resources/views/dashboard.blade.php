@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Welcome back, {{ auth()->user()->name }}!</h1>

    {{-- Stats Section --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 transition-colors">
            <h2 class="text-lg font-semibold mb-4">Total Posts</h2>
            <p class="text-3xl font-bold">{{ $totalPosts }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 transition-colors">
            <h2 class="text-lg font-semibold mb-4">Connections</h2>
            <p class="text-3xl font-bold">{{ $connections }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 transition-colors">
            <h2 class="text-lg font-semibold mb-4">Messages</h2>
            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">{{ $unreadMessages }} unread</p>
        </div>
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow p-6 transition-colors">
            <h2 class="text-lg font-semibold mb-4">Recent Posts</h2>
            <ul class="space-y-3">
                @forelse($recentPosts as $post)
                    <li class="border-b dark:border-gray-700 pb-2">
                        <a href="{{ route('posts.show', $post) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                            {{ Str::limit($post->title, 50) }}
                        </a>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">by {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}</p>
                    </li>
                @empty
                    <li class="text-gray-500 dark:text-gray-400">No recent posts.</li>
                @endforelse
            </ul>
        </div>
    </div>

<!-- Pending Peer Requests -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow transition-colors">
    <h2 class="text-lg font-semibold mb-4">Pending Peer Requests</h2>
    @if($pendingRequests->isEmpty())
        <p class="text-gray-500 dark:text-gray-400 text-sm">No pending requests.</p>
    @else
        <ul class="space-y-3">
            @foreach($pendingRequests as $req)
                <li class="flex items-center justify-between">
                    <span>{{ $req->sender->name }}</span>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('peer_requests.accept', $req) }}">
                            @csrf
                            <button class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">Accept</button>
                        </form>
                        <form method="POST" action="{{ route('peer_requests.decline', $req) }}">
                            @csrf
                            <button class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors">Decline</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
