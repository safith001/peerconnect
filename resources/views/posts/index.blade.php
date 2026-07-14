@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">All Posts</h1>
            <a href="{{ route('posts.create') }}"
               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition duration-200">
                + Create New Post
            </a>
        </div>

        {{-- Posts List --}}
        <div class="space-y-6">
            @forelse ($posts as $post)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-md hover:shadow-lg transition duration-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $post->title }}</h2>
                            <p class="text-gray-700 dark:text-gray-300 mb-3 leading-relaxed">{{ Str::limit($post->content, 150) }}</p>

                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-medium">{{ optional($post->user)->name ?? 'Unknown User' }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>

                            {{-- Like Button --}}
                            <div class="flex items-center gap-3 mt-3">
                                @auth
                                    <form action="{{ route('posts.like', $post->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 text-sm font-medium transition duration-200 {{ $post->isLikedBy(auth()->user()) ? 'text-red-600 dark:text-red-400 hover:text-red-700' : 'text-gray-500 dark:text-gray-400 hover:text-red-500' }}">
                                            @if($post->isLikedBy(auth()->user()))
                                                &#10084;&#65039;
                                            @else
                                                &#128156;
                                            @endif
                                            <span>{{ $post->likes_count ?? $post->likes->count() }}</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400">
                                        &#128156; {{ $post->likes_count ?? $post->likes->count() }}
                                    </span>
                                @endauth

                                <a href="{{ route('posts.show', $post->id) }}"
                                   class="text-sm text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition duration-200">
                                    {{ $post->comments_count ?? $post->comments->count() }} comments
                                </a>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-2 ml-6">
                            {{-- Read More --}}
                            <a href="{{ route('posts.show', $post->id) }}"
                               class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium text-sm transition duration-200">
                                Read More
                            </a>

                            @auth
                                @if (Auth::id() === $post->user_id)
                                    <div class="flex gap-2 mt-2">
                                        {{-- Edit Button --}}
                                        <a href="{{ route('posts.edit', $post->id) }}"
                                           class="inline-flex items-center bg-yellow-400 hover:bg-yellow-500 text-yellow-900 text-sm font-semibold px-3 py-1.5 rounded-md shadow transition duration-200">
                                            Edit
                                        </a>

                                        {{-- Delete Button --}}
                                        <form action="{{ route('posts.destroy', $post->id) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 text-sm font-semibold px-3 py-1.5 rounded-md border border-red-200 dark:border-red-800 shadow transition duration-200">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-8 text-center transition-colors">
                    <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-300 mb-2">No Posts Found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Be the first to create a post!</p>
                    <a href="{{ route('posts.create') }}"
                       class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition duration-200">
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
