@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Post Header --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-md mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

        <div class="flex items-center text-sm text-gray-500 mb-6">
            <span class="font-medium">
                <a href="{{ route('users.show', $post->user) }}" class="text-blue-600 hover:text-blue-800 transition duration-200">
                    {{ optional($post->user)->name ?? 'Unknown User' }}
                </a>
            </span>
            <span class="mx-2">•</span>
            <span>{{ $post->created_at->diffForHumans() }}</span>
        </div>

        <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed">
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
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-md mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📎 Attachments</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($files as $path)
                    <a href="{{ asset('storage/'.$path) }}" target="_blank"
                       class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                        <span class="text-blue-600 mr-2">📄</span>
                        <span class="text-blue-600 hover:text-blue-800 font-medium">
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
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition duration-200">
            ← Back to Posts
        </a>

        @if(auth()->id() === $post->user_id)
            <a href="{{ route('posts.edit', $post->id) }}"
               class="inline-flex items-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 rounded-md shadow transition duration-200">
                ✏️ Edit Post
            </a>

            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this post?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 rounded-md border border-red-200 shadow transition duration-200">
                    🗑️ Delete Post
                </button>
            </form>
        @endif
    </div>

    <hr class="my-8 border-gray-200">

    {{-- Comments Section --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-md">
        <h2 class="text-xl font-bold text-gray-900 mb-6">💬 Comments</h2>

        {{-- Add Comment Form --}}
        @auth
            <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-8">
                @csrf
                <div class="mb-4">
                    <label class="block font-semibold text-gray-700 mb-2">Add your comment</label>
                    <textarea name="body" rows="4"
                              class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Share your thoughts..."
                              required>{{ old('body') }}</textarea>
                    @error('body')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow transition duration-200">
                    💬 Post Comment
                </button>
            </form>
        @else
            <p class="mb-8 p-4 bg-gray-50 border border-gray-200 rounded-lg text-gray-600">
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Login</a> to post a comment.
            </p>
        @endauth

        {{-- Comments List --}}
        <div class="space-y-4">
            @forelse ($post->comments as $comment)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">
                                <a href="{{ route('users.show', $comment->user) }}" class="text-blue-600 hover:text-blue-800 transition duration-200">
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
                                <button class="text-red-600 hover:text-red-800 text-sm font-medium transition duration-200">
                                    🗑️ Delete
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="text-gray-800 leading-relaxed">
                        {!! nl2br(e($comment->body)) !!}
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <div class="text-4xl mb-2">💭</div>
                    <p>No comments yet. Be the first to comment!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
