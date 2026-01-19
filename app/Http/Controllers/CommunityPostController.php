<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use Illuminate\Http\Request;

class CommunityPostController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()?->id;
        
        return CommunityPost::with(['user', 'likes', 'comments.user'])
            ->where('status', 'approved')
            ->latest()
            ->get()
            ->map(function($post) use ($userId) {
                $post->is_owner = $post->user_id === $userId;
                return $post;
            });
    }

    public function store(Request $request)
    {
        // Check if user is warned
        if ($request->user()->community_warn_until && $request->user()->community_warn_until > now()) {
            return response()->json([
                'message' => 'Anda dilarang posting di komunitas karena menerima peringatan dari admin',
                'warn_until' => $request->user()->community_warn_until,
            ], 403);
        }

        $data = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post = CommunityPost::create([
            'user_id' => $request->user()->id,
            'content' => $data['content'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Post created, waiting for admin approval',
            'post' => $post,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $post = CommunityPost::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post->update([
            'content' => $request->content,
            'status' => 'pending', // edit → review ulang
        ]);

        return response()->json([
            'message' => 'Post updated and sent for review',
            'post' => $post,
        ]);
    }
 
    public function destroy(Request $request, $id)
    {
        $post = CommunityPost::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
}
