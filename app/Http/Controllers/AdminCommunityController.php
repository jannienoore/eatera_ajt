<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use App\Models\CommunityComment;
use App\Models\PostReport;
use App\Models\CommentReport;
use Illuminate\Http\Request;

class AdminCommunityController extends Controller
{
    public function pendingPosts()
    {
        return CommunityPost::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();
    }

    public function approvePost($id)
    {
        $post = CommunityPost::findOrFail($id);

        $post->update([
            'status' => 'approved',
        ]);

        return response()->json([
            'message' => 'Post approved',
            'post' => $post,
        ]);
    }

    public function rejectPost($id)
    {
        $post = CommunityPost::findOrFail($id);

        $post->update([
            'status' => 'rejected',
        ]);

        return response()->json([
            'message' => 'Post rejected',
        ]);
    }

    public function deletePost($id)
    {
        CommunityPost::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Post deleted',
        ]);
    }

    public function postReports()
    {
        return PostReport::with(['post', 'reporter'])->latest()->get();
    }

    public function deleteReport($reportId)
    {
        PostReport::findOrFail($reportId)->delete();

        return response()->json([
            'message' => 'Report deleted',
        ]);
    }

    public function commentReports()
    {
        return CommentReport::with(['comment.user', 'post', 'reporter'])->latest()->get();
    }

    public function deleteCommentReport($reportId)
    {
        CommentReport::findOrFail($reportId)->delete();

        return response()->json([
            'message' => 'Comment report deleted',
        ]);
    }

    public function deleteComment($postId, $commentId)
    {
        // Admin can delete any comment
        $comment = CommunityComment::where('id', $commentId)
            ->where('post_id', $postId)
            ->firstOrFail();

        $comment->delete();

        return response()->json(['message' => 'Comment deleted']);
    }
}
