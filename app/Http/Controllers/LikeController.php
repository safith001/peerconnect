<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $user = Auth::user();

        if ($post->isLikedBy($user)) {
            $post->likes()->where('user_id', $user->id)->delete();
            return back()->with('success', 'Post unliked');
        }

        $post->likes()->create(['user_id' => $user->id]);
        return back()->with('success', 'Post liked!');
    }
}
