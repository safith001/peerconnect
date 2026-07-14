@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Post Header --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-md mb-6 transition-colors">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $post->title }}</h1>

        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-6">
            <span class="font-medium">
                <a href="{{ route('users.show', $post->user) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition duration-200">
                    {{ optional($post->user)->name ?? 'Unknown User' }}
                </a>
            </span>
            <span class="mx-2">•</span>
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
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-md mb-6 transition-colors">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Attachments</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($files as $path)
                    <a href="{{ asset('storage/'.$path) }}" target="_blank"
                       class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                        <span class="text-blue-600 dark:text-blue-400 mr-2">&#128196;</span>
                        <span class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                            {{ basename($path) }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Action Buttons --}}
    <div class="flex flex-wrap gap-3 mb-8">
        <a href="{{ route('posts.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">
            &larr; Back to Posts
        </a>

        {{-- Like Button --}}
        @auth
            <form action="{{ route('posts.like', $post->id) }}" method="POST">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-md shadow transition duration-200 {{ $post->isLikedBy(auth()->user()) ? 'bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800' : 'bg-gray-100 dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900/30 text-gray-700 dark:text-gray-300 hover:text-red-600 border border-gray-200 dark:border-gray-600' }}">
                    @if($post->isLikedBy(auth()->user()))
                        &#10084;&#65039; Liked
                    @else
                        &#128156; Like
                    @endif
                    <span class="font-semibold">{{ $post->likes->count() }}</span>
                </button>
            </form>
        @else
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-md border border-gray-200 dark:border-gray-600">
                &#128156; {{ $post->likes->count() }} likes
            </span>
        @endauth

        @if(auth()->id() === $post->user_id)
            <a href="{{ route('posts.edit', $post->id) }}"
               class="inline-flex items-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 rounded-md shadow transition duration-200">
                Edit Post
            </a>

            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this post?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 rounded-md border border-red-200 dark:border-red-800 shadow transition duration-200">
                    Delete Post
                </button>
            </form>
        @endif
    </div>

    <hr class="my-8 border-gray-200 dark:border-gray-700">

    {{-- Comments Section --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-md transition-colors">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Comments</h2>

        {{-- Add Comment Form --}}
        @auth
            <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-8">
                @csrf
                <div class="mb-4">
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-2">Add your comment</label>
                    <textarea name="body" rows="4"
                              class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Share your thoughts..."
                              required>{{ old('body') }}</textarea>
                    @error('body')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow transition duration-200">
                    Post Comment
                </button>
            </form>
        @else
            <p class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-600 dark:text-gray-300">
                <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">Login</a> to post a comment.
            </p>
        @endauth

        {{-- Comments List --}}
        <div class="space-y-4">
            @forelse ($post->comments as $comment)
                <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">
                                <a href="{{ route('users.show', $comment->user) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition duration-200">
                                    {{ optional($comment->user)->name ?? 'Unknown User' }}
                                </a>
                            </span>
                            <span class="mx-2">•</span>
                            <span>{{ $comment->created_at->diffForHumans() }}</span>
                        </div>

                        @if (auth()->id() === $comment->user_id)
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm font-medium transition duration-200">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="text-gray-800 dark:text-gray-200 leading-relaxed">
                        {!! nl2br(e($comment->body)) !!}
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <p>No comments yet. Be the first to comment!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
