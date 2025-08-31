@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/mypage.css')}}">
@endsection

@section('content')
<div class="mypage-form">
  <h2 class="mypage-form__heading content__heading">プロフィール設定</h2>
  <div class="mypage-form__inner">
    <div>
      <div>
        画像
      <div>
      <div>
        <button class="">画像を選択する</button>
      <div>    
    </div>
    <form class="register-form__form" action="/register" method="post">
      @csrf
      <div class="register-form__group">
        <label class="register-form__label" for="name">ユーザ名</label>
        <input class="register-form__input" type="text" name="name" id="name">
        <p class="register-form__error-message">
          @error('name')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="register-form__group">
        <label class="register-form__label" for="email">メールアドレス</label>
        <input class="register-form__input" type="mail" name="email" id="email">
        <p class="register-form__error-message">
          @error('email')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="register-form__group">
        <label class="register-form__label" for="password">パスワード</label>
        <input class="register-form__input" type="password" name="password" id="password">
      </div>
      <div class="register-form__group">
        <label class="register-form__label" for="password">確認用パスワード</label>
        <input class="register-form__input" type="password" name="password" id="password">
        <p class="register-form__error-message">
          @error('password')
          {{ $message }}
          @enderror
        </p>
      </div>
      <input class="register-form__btn btn" type="submit" value="登録する">
    </form>
    <a class="register-form__link-login" href="/login">ログインはこちら</a>
  </div>
</div>
@endsection('content')