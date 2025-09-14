<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Condition;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 登録画面の表示
    public function show_register()
    {
        return view('auth.register');  
    }
    
    // 登録時の処理
    public function store_user(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user); 

        return redirect()->route('mypage.edit');
    }

    // プロフィール編集画面表示
    public function mypage_edit()
    {
        $auth_user = Auth::user();

        return view('auth.mypage_edit', compact('auth_user'));
    }

    // プロフィールの更新
    public function mypage_update(Request $request)
    {
        $userId = Auth::id(); // ログインユーザーID
        $user = User::findOrFail($userId);

        $user->name = $request->name;
        $user->zipcode = $request->zipcode;
        $user->address = $request->address;
        $user->building = $request->building;

        // 新しい画像がアップロードされた場合
        if ($request->hasFile('img_url')) {
            $file     = $request->file('img_url');
            $filename = 'user_id-' . $user->id . '.' . $file->getClientOriginalExtension(); // 例: 5.jpg
            $path     = $file->storeAs('profile_images', $filename, 'public');

            // DBに保存
            $user->img_url = $path;
        }

        $user->save();

        return redirect('/');
    }

    // プロフィール画面表示
    public function mypage()
    {
        $products = Product::with('condition')->get();
        $conditions = Condition::all();
        $userId = Auth::id();

        return view('auth.mypage', compact('products', 'conditions', 'userId'));
    }

    // ログイン時の処理
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
