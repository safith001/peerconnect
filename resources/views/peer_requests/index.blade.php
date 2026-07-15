@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Peer Requests</h1>
        <a href="{{ route('peers.index') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
            Find Peers
        </a>
    </div>

    {{-- Incoming Requests --}}
    <div class="mb-10">
        <div class="flex items-center gap-2 mb-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Incoming Requests</h2>
            <span class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold px-2 py-0.5 rounded-full">{{ $incoming->count() }}</span>
        </div>

        @if($incoming->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 font-medium">No incoming requests</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">When someone sends you a peer request, it will appear here</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($incoming as $req)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold flex-shrink-0 shadow-sm">
                            {{ strtoupper(substr($req->sender->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $req->sender->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $req->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex gap-2 flex-shrink-0">
                            <form method="POST" action="{{ route('peer_requests.accept', $req) }}">
                                @csrf
                                <button class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                    Accept
                                </button>
                            </form>
                            <form method="POST" action="{{ route('peer_requests.decline', $req) }}">
                                @csrf
                                <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    Decline
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Sent Requests --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Sent Requests</h2>
            <span class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold px-2 py-0.5 rounded-full">{{ $sent->count() }}</span>
        </div>

        @if($sent->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 font-medium">No sent requests</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Browse peers to send your first request</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($sent as $req)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white text-lg font-bold flex-shrink-0 shadow-sm">
                            {{ strtoupper(substr($req->receiver->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $req->receiver->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $req->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            @if($req->status === 'pending')
                                <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 text-xs font-semibold rounded-full">Pending</span>
                            @elseif($req->status === 'accepted')
                                <span class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs font-semibold rounded-full">Accepted</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 text-xs font-semibold rounded-full">Declined</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection