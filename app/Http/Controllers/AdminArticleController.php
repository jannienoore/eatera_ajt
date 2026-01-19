<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class AdminArticleController extends Controller
{
    public function index()
    {
        return Article::with('admin:id,name')
            ->select('id', 'title', 'content', 'status', 'admin_id', 'created_at', 'updated_at')
            ->latest()
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);

        $article = Article::create([
            ...$data,
            'admin_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Article created',
            'article' => $article,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'status' => 'sometimes|in:draft,published',
        ]);

        $article->update($data);

        return response()->json([
            'message' => 'Article updated',
            'article' => $article,
        ]);
    }

    public function show($id)
    {
        return Article::with('admin')->findOrFail($id);
    }
}
