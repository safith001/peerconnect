@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Peer Requests</h1>

    {{-- Incoming Requests --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-3">Incoming Requests</h2>
        @if($incoming->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">No incoming requests</p>
        @else
            <div class="space-y-3">
                @foreach($incoming as $req)
                    <div class="flex items-center justify-between bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg p-3 transition-colors">
                        <span>{{ $req->sender->name }}</span>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('peer_requests.accept', $req) }}">
                                @csrf
                                <button class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">Accept</button>
                            </form>
                            <form method="POST" action="{{ route('peer_requests.decline', $req) }}">
                                @csrf
                                <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">Decline</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Sent Requests --}}
    <div>
        <h2 class="text-lg font-semibold mb-3">Sent Requests</h2>
        @if($sent->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">No sent requests</p>
        @else
            <div class="space-y-3">
                @foreach($sent as $req)
                    <div class="flex items-center justify-between bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg p-3 transition-colors">
                        <span>{{ $req->receiver->name }}</span>
                        <span class="text-sm text-gray-600 dark:text-gray-400 capitalize">{{ $req->status }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
