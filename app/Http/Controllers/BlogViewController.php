<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\StrRandom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Facades\Illuminate\Support\Str;


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

    public function detail(Blog $blog)
    {
        if ($blog->isClosed()) abort(403);

        $random = app(StrRandom::class)->random(10);

        return view('blog.detail', compact('blog', 'random'));
    }
}
