@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">✏️ Edit Post</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>⚠ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg border space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}"
                       class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                <textarea name="content"
                          class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          rows="6" required>{{ old('content', $post->content) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Attachment (optional)</label>
                <input type="file" name="attachment"
                       class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-sm text-gray-500 mt-1">Leave empty to keep existing attachments</p>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('posts.show', $post->id) }}"
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-200">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow transition duration-200">
                    💾 Update Post
                </button>
            </div>
        </form>
    </div>
@endsection
