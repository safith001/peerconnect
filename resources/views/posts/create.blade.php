@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto p-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">📝 Create New Post</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>⚠ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg border space-y-5">
            @csrf

            <div>
                <label class="block font-medium text-gray-700 mb-2">Title</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-2">Content</label>
                <textarea name="content" rows="6"
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          required>{{ old('content') }}</textarea>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-2">Attachments (optional)</label>
                <input type="file" name="attachments[]" multiple
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-sm text-gray-500 mt-1">You can select multiple files</p>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('posts.index') }}"
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-200">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow transition duration-200">
                    📤 Create Post
                </button>
            </div>
        </form>
    </div>
@endsection
