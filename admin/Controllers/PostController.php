<?php

namespace Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('user');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest()->paginate(20);

        return view('admin::posts.index', compact('posts'));
    }

    public function destroy(Post $post)
    {
        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return back()->with('success', 'Post has been deleted.');
    }
}
