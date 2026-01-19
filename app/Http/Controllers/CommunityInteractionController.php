<?php

namespace App\Http\Controllers;

use App\Models\CommunityLike;
use App\Models\CommunityComment;
use App\Models\CommunityPost;
use Illuminate\Http\Request;

class CommunityInteractionController extends Controller
{
    public function toggleLike(Request $request, $postId)
    {
        $post = CommunityPost::where('id', $postId)
            ->where('status', 'approved')
            ->firstOrFail();

        $like = CommunityLike::where('post_id', $postId)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Unliked']);
        }

        CommunityLike::create([
            'post_id' => $postId,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Liked']);
    }

    public function comment(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $post = CommunityPost::where('id', $postId)
            ->where('status', 'approved')
            ->firstOrFail();

        $comment = CommunityComment::create([
            'post_id' => $postId,
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Comment added',
            'comment' => $comment,
        ], 201);
    }

    public function updateComment(Request $request, $postId, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = CommunityComment::where('id', $commentId)
            ->where('post_id', $postId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $comment->update([
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Comment updated',
            'comment' => $comment,
        ]);
    }

    public function deleteComment(Request $request, $postId, $commentId)
    {
        $comment = CommunityComment::where('id', $commentId)
            ->where('post_id', $postId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $comment->delete();

        return response()->json(['message' => 'Comment deleted']);
    }
}