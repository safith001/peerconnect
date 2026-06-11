@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Welcome back, {{ auth()->user()->name }}!</h1>

    {{-- Stats Section --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Total Posts</h2>
            <p class="text-3xl font-bold">{{ $totalPosts }}</p>
            {{-- <p class="text-xs text-green-600 mt-1">+3 this week</p> --}}
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Connections</h2>
            <p class="text-3xl font-bold">{{ $connections }}</p>
            {{-- <p class="text-xs text-green-600 mt-1">+12 this week</p> --}}
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Messages</h2>
            {{-- <p class="text-3xl font-bold">{{ $messagesCount }}</p> --}}
            <p class="text-xs text-blue-600 mt-1">{{ $unreadMessages }} unread</p>
        </div>
        <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Recent Posts</h2>
            <ul class="space-y-3">
                @forelse($recentPosts as $post)
                    <li class="border-b pb-2">
                        <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">
                            {{ Str::limit($post->title, 50) }}
                        </a>
                        <p class="text-xs text-green-600 mt-1">by {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}</p>
                    </li>
                @empty
                    <li class="text-gray-500">No recent posts.</li>
                @endforelse
            </ul>
        </div>

        {{--
         <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-gray-600 text-sm">Profile Views</h2>
            <p class="text-3xl font-bold">{{ $profileViews }}</p>
            <p class="text-xs text-green-600 mt-1">+18% this month</p>
        </div>
         --}}
    </div>



    {{-- Recent Posts + Quick Actions
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Recent Posts</h2>
            <ul class="space-y-3">
                @forelse($recentPosts as $post)
                    <li class="border-b pb-2">
                        <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">
                            {{ Str::limit($post->title, 50) }}
                        </a>
                        <p class="text-xs text-green-600 mt-1">by {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}</p>
                    </li>
                @empty
                    <li class="text-gray-500">No recent posts.</li>
                @endforelse
            </ul>
        </div>


            <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('posts.create') }}" class="block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Create Post</a>
                <a href="{{ route('peers.index') }}" class="block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Find Peers</a>
                <a href="{{ route('conversations.index') }}" class="block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Check Messages</a>
            </div>
        </div>



        </div>
    </div> --}}

{{-- recent activity
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
    <!-- Recent Activity -->
<div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-lg font-semibold mb-4">Recent Activity</h2>
    <ul class="space-y-3 text-sm">
        @foreach($recentPosts as $post)
            <li>📝 New Post: <strong>{{ $post->title }}</strong></li>
        @endforeach
        @foreach($recentMessages as $msg)
    <li>💬 <strong>{{ $msg->sender->name }}</strong>:
        <span class="text-gray-600">{{ Str::limit($msg->body, 30) }}</span>
    </li>
@endforeach
    </ul>
</div>      --}}

    <!-- Profile Status
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-lg font-semibold mb-4">Profile Status</h2>
        <p class="text-sm mb-2">Your profile is {{ $profileCompletion }}% complete</p>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $profileCompletion }}%"></div>
        </div>
    </div>
</div>           -->

<!-- Pending Peer Requests -->
<div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-lg font-semibold mb-4">Pending Peer Requests</h2>
    @if($pendingRequests->isEmpty())
        <p class="text-gray-500 text-sm">No pending requests.</p>
    @else
        <ul class="space-y-3">
            @foreach($pendingRequests as $req)
                <li class="flex items-center justify-between">
                    <span>{{ $req->sender->name }}</span>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('peer_requests.accept', $req) }}">
                            @csrf
                            <button class="px-3 py-1 bg-blue-600 text-white text-sm rounded ">Accept</button>
                        </form>
                        <form method="POST" action="{{ route('peer_requests.decline', $req) }}">
                            @csrf
                            <button class="px-3 py-1 bg-red-600 text-white text-sm rounded ">Decline</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
