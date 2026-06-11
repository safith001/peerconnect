@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900">📋 All Posts</h1>
            <a href="{{ route('posts.create') }}"
               class="inline-flex items-center bg-blue-600 hover:bg-blue-900 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition duration-200">
                + Create New Post
            </a>
        </div>

        {{-- Posts List --}}
        <div class="space-y-6">
            @forelse ($posts as $post)
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-md hover:shadow-lg transition duration-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $post->title }}</h2>
                            <p class="text-gray-700 mb-3 leading-relaxed">{{ Str::limit($post->content, 150) }}</p>

                            <div class="flex items-center text-sm text-gray-500">
                                <span class="font-medium">{{ optional($post->user)->name ?? 'Unknown User' }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-2 ml-6">
                            {{-- Read More --}}
                            <a href="{{ route('posts.show', $post->id) }}"
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium text-sm transition duration-200">
                                📖 Read More
                            </a>

                            @auth
                                @if (Auth::id() === $post->user_id)
                                    <div class="flex gap-2 mt-2">
                                        {{-- Edit Button --}}
                                        <a href="{{ route('posts.edit', $post->id) }}"
                                           class="inline-flex items-center bg-yellow-400 hover:bg-yellow-500 text-yellow-900 text-sm font-semibold px-3 py-1.5 rounded-md shadow transition duration-200">
                                            ✏️ Edit
                                        </a>

                                        {{-- Delete Button --}}
                                        <form action="{{ route('posts.destroy', $post->id) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center bg-red-50 hover:bg-red-100 text-red-700 text-sm font-semibold px-3 py-1.5 rounded-md border border-red-200 shadow transition duration-200">
                                                🗑️ Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-200 rounded-xl p-8 text-center">
                    <div class="text-6xl mb-4">📝</div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Posts Found</h3>
                    <p class="text-gray-500 mb-4">Be the first to create a post!</p>
                    <a href="{{ route('posts.create') }}"
                       class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition duration-200">
                        ➕ Create Your First Post
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
