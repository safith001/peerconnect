<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * List all posts (newest first) with author eager-loaded.
     */
    public function index()
    {
        $posts = Post::with('user')->latest()->get();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a new post with (optional) multiple attachments.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'content'        => ['required', 'string'],
            'attachments'    => ['nullable', 'array'],
            'attachments.*'  => ['file', 'mimes:pdf,jpg,jpeg,png,doc,docx,txt', 'max:2048'],
        ]);

        $storedFiles = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '' . Str::random(6) . '' . $file->getClientOriginalName();
                $storedFiles[] = $file->storeAs('attachments', $filename, 'public');
            }
        }

        Post::create([
            'title'      => $request->title,
            'content'    => $request->content,
            'attachment' => $storedFiles,   // cast to array in Post model
            'user_id'    => Auth::id(),
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    /**
     * Show a single post (with author + comments).
     */
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user']);
        return view('posts.show', compact('post'));
    }

    /**
     * Show edit form (owner only).
     */
    public function edit(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update a post and optionally replace attachments (owner only).
     */
    public function update(Request $request, Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'content'        => ['required', 'string'],
            'attachments'    => ['nullable', 'array'],
            'attachments.*'  => ['file', 'mimes:pdf,jpg,jpeg,png,doc,docx,txt', 'max:2048'],
        ]);

        $filePaths = $post->attachment ?? [];

        // If new files uploaded, replace existing set
        if ($request->hasFile('attachments')) {
            // delete old ones
            $existing = $post->attachment;
            if (is_string($existing)) {
                $existing = json_decode($existing, true) ?? [];
            }
            if (is_array($existing)) {
                foreach ($existing as $file) {
                    Storage::disk('public')->delete($file);
                }
            }

            $filePaths = [];
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '' . Str::random(6) . '' . $file->getClientOriginalName();
                $filePaths[] = $file->storeAs('attachments', $filename, 'public');
            }
        }

        $post->update([
            'title'      => $request->title,
            'content'    => $request->content,
            'attachment' => $filePaths,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    /**
     * Delete a post and its attachments (owner only).
     */
    public function destroy(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $files = $post->attachment;
        if (is_string($files)) {
            $files = json_decode($files, true) ?? [];
        }
        if (is_array($files)) {
            foreach ($files as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }
}
