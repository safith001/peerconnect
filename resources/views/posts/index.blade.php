@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Posts</h1>
            <a href="{{ route('posts.create') }}"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg shadow-blue-600/20 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Post
            </a>
        </div>

        {{-- Posts List --}}
        <div class="space-y-5">
            @forelse ($posts as $post)
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-lg transition-all">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex-1 min-w-0">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $post->title }}</h2>
                            <p class="text-gray-600 dark:text-gray-400 mb-3 leading-relaxed">{{ Str::limit($post->content, 200) }}</p>

                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-[9px] font-bold flex-shrink-0">
                                    {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="font-medium">{{ $post->user->name ?? 'Unknown' }}</span>
                                <span>•</span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>

                            <div class="flex items-center gap-4 mt-3">
                                @auth
                                    <form action="{{ route('posts.like', $post->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 text-sm font-medium transition-colors {{ $post->isLikedBy(auth()->user()) ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400 hover:text-red-500' }}">
                                            <svg class="w-4 h-4" fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                            <span>{{ $post->likes_count ?? $post->likes->count() }}</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        {{ $post->likes_count ?? $post->likes->count() }}
                                    </span>
                                @endauth

                                <a href="{{ route('posts.show', $post->id) }}"
                                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    {{ $post->comments_count ?? $post->comments->count() }} comments
                                </a>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-2 flex-shrink-0">
                            <a href="{{ route('posts.show', $post->id) }}"
                               class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold transition-colors">
                                Read &rarr;
                            </a>

                            @auth
                                @if (Auth::id() === $post->user_id)
                                    <div class="flex gap-2">
                                        <a href="{{ route('posts.edit', $post->id) }}"
                                           class="text-xs px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-lg hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition-colors font-medium">
                                            Edit
                                        </a>
                                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Delete this post?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-xs px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors font-medium">Delete</button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-12 text-center border border-gray-200/50 dark:border-gray-700/50">
                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-600 dark:text-gray-300 mb-2">No Posts Yet</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Be the first to share something!</p>
                    <a href="{{ route('posts.create') }}"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg shadow-blue-600/20 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Your First Post
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if(method_exists($posts, 'links'))
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
