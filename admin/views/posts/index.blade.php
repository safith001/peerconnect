@extends('admin::layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manage Posts</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">&larr; Back to Dashboard</a>
    </div>

    <form method="GET" class="flex gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or content..."
               class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Search</button>
    </form>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">Title</th>
                        <th class="text-left px-4 py-3 font-semibold">Author</th>
                        <th class="text-left px-4 py-3 font-semibold">Comments</th>
                        <th class="text-left px-4 py-3 font-semibold">Likes</th>
                        <th class="text-left px-4 py-3 font-semibold">Posted</th>
                        <th class="text-right px-4 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($posts as $post)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                            <td class="px-4 py-3">
                                <span class="font-medium text-gray-900 dark:text-white line-clamp-1">{{ $post->title }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $post->user->name ?? 'Unknown' }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $post->comments_count ?? $post->comments->count() }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $post->likes_count ?? $post->likes->count() }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">{{ $post->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3 text-right">
                                <form method="POST" action="{{ route('admin.posts.destroy', $post) }}"
                                      onsubmit="return confirm('Delete this post and all its comments? This cannot be undone.')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-sm text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>
@endsection
