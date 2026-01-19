<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        return Article::where('status', 'published')
            ->with('admin')
            ->latest()
            ->get();
    }

    public function show($id)
    {
        return Article::where('status', 'published')
            ->with('admin')
            ->findOrFail($id);
    }
}
