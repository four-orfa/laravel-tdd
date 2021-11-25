<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserLoginController extends Controller
{
    public function index()
    {
        return view('mypage/login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'email:filter'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('mypage/blogs');
        } else {
            throw ValidationException::withMessages(['email' => 'Invalid email or password.']);
        }

        return redirect('mypage/login');
    }
}
