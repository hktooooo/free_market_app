<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //登録時の処理
    public function show_register()
    {
        return view('auth.register');  
    }
    
    public function mypage()
    {
        return view('auth.mypage');
    }

    public function mypage_edit()
    {
        return view('auth.mypage_edit');
    }

    public function store_user(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user); 

        return redirect()->route('mypage_edit');
    }

    //ログイン時の処理
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }
}
