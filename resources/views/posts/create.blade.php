@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Create New Post</h1>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-md transition-colors">
        @csrf

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                   class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content</label>
            <textarea name="content" id="content" rows="6" required
                      class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">{{ old('content') }}</textarea>
        </div>

        <div class="mb-6">
            <label for="attachments" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attachments (optional)</label>
            <input type="file" name="attachments[]" id="attachments" multiple
                   class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PDF, JPG, PNG, DOC, TXT — max 2MB each</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('posts.index') }}"
               class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition-colors">
                Create Post
            </button>
        </div>
    </form>
</div>
@endsection
