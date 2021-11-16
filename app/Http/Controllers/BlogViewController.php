<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogViewController extends Controller
{
    public function index()
    {
        // $blogs = Blog::get();
        // DB::enableQueryLog();
        $blogs = Blog::with('user')
            // ->where('status', Blog::OPEN)
            ->open() // -> scopeOpen
            ->withCount('comments')
            ->orderByDesc('comments_count')
            ->get();
        // dump(DB::getQueryLog());
        return view('index', compact('blogs'));
    }
}
