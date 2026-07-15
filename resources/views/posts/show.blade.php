@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Post --}}
    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-6 md:p-8 border border-gray-200/50 dark:border-gray-700/50 shadow-lg mb-6 transition-all">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $post->title }}</h1>

        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">
                {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
            </div>
            <a href="{{ route('users.show', $post->user) }}" class="font-medium text-blue-600 dark:text-blue-400 hover:underline">{{ $post->user->name ?? 'Unknown' }}</a>
            <span>•</span>
            <span>{{ $post->created_at->diffForHumans() }}</span>
        </div>

        <div class="prose prose-lg max-w-none text-gray-800 dark:text-gray-200 leading-relaxed">
            {!! nl2br(e($post->content)) !!}
        </div>
    </div>

    {{-- Attachments --}}
    @php
        $files = is_array($post->attachment)
            ? $post->attachment
            : (json_decode($post->attachment, true) ?? []);
    @endphp

    @if(!empty($files))
        <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50 shadow-lg mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Attachments</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($files as $path)
                    <a href="{{ asset('storage/'.$path) }}" target="_blank"
                       class="flex items-center gap-3 p-3 bg-white/50 dark:bg-gray-700/50 border border-gray-200/50 dark:border-gray-700/50 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/80 transition-all">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-blue-600 dark:text-blue-400 font-medium text-sm truncate">{{ basename($path) }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Action Buttons --}}
    <div class="flex flex-wrap gap-3 mb-8">
        <a href="{{ route('posts.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm text-gray-700 dark:text-gray-300 rounded-xl border border-gray-200/50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/80 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Posts
        </a>

        @auth
            <form action="{{ route('posts.like', $post->id) }}" method="POST">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl shadow-sm transition-all {{ $post->isLikedBy(auth()->user()) ? 'bg-red-50/80 dark:bg-red-900/30 text-red-600 dark:text-red-400 border border-red-200/50 dark:border-red-800/50' : 'bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm text-gray-600 dark:text-gray-400 border border-gray-200/50 dark:border-gray-700/50 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-500' }}">
                    <svg class="w-4 h-4" fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span class="font-semibold">{{ $post->likes->count() }}</span>
                </button>
            </form>
        @else
            <span class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm text-gray-500 dark:text-gray-400 rounded-xl border border-gray-200/50 dark:border-gray-700/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                {{ $post->likes->count() }}
            </span>
        @endauth

        {{-- Report Post --}}
        @auth
            @if(auth()->id() !== $post->user_id)
                <button onclick="document.getElementById('report-post-{{ $post->id }}').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm text-gray-500 dark:text-gray-400 rounded-xl border border-gray-200/50 dark:border-gray-700/50 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-500 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                    Report
                </button>

                <div id="report-post-{{ $post->id }}" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl border border-gray-200/50 dark:border-gray-700/50">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Report Post</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Why are you reporting this post?</p>
                        <form method="POST" action="{{ route('report.post', $post) }}">
                            @csrf
                            <textarea name="reason" rows="3" required
                                      class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4"
                                      placeholder="Explain why this post violates the rules..."></textarea>
                            <div class="flex gap-2 justify-end">
                                <button type="button" onclick="this.closest('.fixed').classList.add('hidden')"
                                        class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Cancel</button>
                                <button class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 shadow transition-all">Submit Report</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endauth

        @if(auth()->id() === $post->user_id)
            <a href="{{ route('posts.edit', $post->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-yellow-100/80 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-xl border border-yellow-200/50 dark:border-yellow-800/50 hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition-all shadow-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Post
            </a>

            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline"
                  onsubmit="return confirm('Delete this post and all comments?');">
                @csrf
                @method('DELETE')
                <button class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-100/80 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-xl border border-red-200/50 dark:border-red-800/50 hover:bg-red-200 dark:hover:bg-red-900/50 transition-all shadow-sm font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </form>
        @endif
    </div>

    <hr class="my-8 border-gray-200/50 dark:border-gray-700/50">

    {{-- Comments Section --}}
    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-6 md:p-8 border border-gray-200/50 dark:border-gray-700/50 shadow-lg transition-all">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Comments</h2>

        @auth
            <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-8">
                @csrf
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Add your comment</label>
                <textarea name="body" rows="3"
                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3"
                          placeholder="Share your thoughts..."
                          required>{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-600/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Post Comment
                </button>
            </form>
        @else
            <p class="mb-8 p-4 bg-gray-50/50 dark:bg-gray-700/50 border border-gray-200/50 dark:border-gray-700/50 rounded-xl text-gray-600 dark:text-gray-400 text-sm">
                <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">Login</a> to post a comment.
            </p>
        @endauth

        <div class="space-y-4">
            @forelse ($post->comments as $comment)
                <div class="bg-gray-50/50 dark:bg-gray-700/50 border border-gray-200/50 dark:border-gray-700/50 rounded-xl p-4 transition-all">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-[9px] font-bold flex-shrink-0">
                                {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <a href="{{ route('users.show', $comment->user) }}" class="font-medium text-blue-600 dark:text-blue-400 hover:underline">{{ $comment->user->name ?? 'Unknown' }}</a>
                            <span>•</span>
                            <span>{{ $comment->created_at->diffForHumans() }}</span>
                        </div>

                        @if (auth()->id() === $comment->user_id)
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this comment?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-xs font-medium transition-colors">Delete</button>
                            </form>
                        @endif
                    </div>

                    <div class="text-gray-800 dark:text-gray-200 leading-relaxed">
                        {!! nl2br(e($comment->body)) !!}
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <p>No comments yet. Be the first!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
