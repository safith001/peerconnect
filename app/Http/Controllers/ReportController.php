<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function storePost(Request $request, Post $post)
    {
        $request->validate(['reason' => 'required|string|max:1000']);

        $exists = Report::where('reportable_type', Post::class)
            ->where('reportable_id', $post->id)
            ->where('reported_by', auth()->id())
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already reported this post.');
        }

        Report::create([
            'reportable_id' => $post->id,
            'reportable_type' => Post::class,
            'reported_by' => auth()->id(),
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Post has been reported. An admin will review it.');
    }

    public function storeComment(Request $request, Comment $comment)
    {
        $request->validate(['reason' => 'required|string|max:1000']);

        $exists = Report::where('reportable_type', Comment::class)
            ->where('reportable_id', $comment->id)
            ->where('reported_by', auth()->id())
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already reported this comment.');
        }

        Report::create([
            'reportable_id' => $comment->id,
            'reportable_type' => Comment::class,
            'reported_by' => auth()->id(),
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Comment has been reported. An admin will review it.');
    }
}
