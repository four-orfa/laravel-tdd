<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogMypageController extends Controller
{
    public function index()
    {
        // $blogs = Blog::where('user_id', auth()->user()->id)->get();
        // refactoring
        $blogs = auth()->user()->blogs;

        return view('mypage.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('mypage.blog.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateInput();

        $data = $request->all(['title', 'body']);
        $data['status'] = $request->boolean('status');

        $blog = auth()->user()->blogs()->create($data);

        return redirect('mypage/blogs/edit/' . $blog->id);
    }

    public function edit(Blog $blog, Request $request)
    {
        if ($request->user()->isNot($blog->user)) {
            abort(403);
        }

        $data = old() ?: $blog;

        return view('mypage.blog.edit', compact('blog', 'data'));
    }

    public function update(Blog $blog)
    {
        // authenticate

    }

    private function validateInput()
    {
        return request()->validate([
            'title' => ['required', 'max:255'],
            'body' => ['required'],
        ]);
    }
}
