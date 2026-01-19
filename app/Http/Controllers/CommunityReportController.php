<?php

namespace App\Http\Controllers;

use App\Models\PostReport;
use App\Models\CommentReport;
use Illuminate\Http\Request;

class CommunityReportController extends Controller
{
    // 🚨 REPORT POST
    public function reportPost(Request $request, $postId)
    {
        $post = \App\Models\CommunityPost::findOrFail($postId);
        
        // Jangan biarkan owner post melaporkan post mereka sendiri
        if ($post->user_id === $request->user()->id) {
            return response()->json([
                'message' => 'Anda tidak bisa melaporkan post milik Anda sendiri'
            ], 403);
        }

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        PostReport::create([
            'post_id' => $postId,
            'reported_by' => $request->user()->id,
            'reason' => $request->reason,
        ]);

        return response()->json(['message' => 'Post reported']);
    }

    // REPORT COMMENT
    public function reportComment(Request $request, $postId, $commentId)
    {
        // Verify comment exists
        $comment = \App\Models\CommunityComment::where('id', $commentId)
            ->where('post_id', $postId)
            ->firstOrFail();

        // Jangan biarkan owner comment melaporkan comment mereka sendiri
        if ($comment->user_id === $request->user()->id) {
            return response()->json([
                'message' => 'Anda tidak bisa melaporkan comment milik Anda sendiri'
            ], 403);
        }

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // Create report
        CommentReport::create([
            'comment_id' => $commentId,
            'post_id' => $postId,
            'reported_by' => $request->user()->id,
            'reason' => $request->reason,
        ]);

        return response()->json(['message' => 'Comment reported']);
    }
}