@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/mypage.css')}}">
@endsection

@section('content')
<div class="mypage-form">
  <h2 class="mypage-form__heading content__heading">プロフィール設定</h2>
  <div class="mypage-form__inner">
    <form class="register-form__form" action="/" method="post">
      @csrf
      <!-- <input type="hidden" name="id"> ユーザIDが必要 あとで直す -->
      <div>
        <div>
          画像
        </div>
        <div>
          <button class="">画像を選択する</button>
        </div>    
      </div>
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
        <label class="register-form__label" for="zipcode">郵便番号</label>
        <input class="register-form__input" type="text" name="zipcode" id="zipcode">
        <p class="register-form__error-message">
          @error('zipcode')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="register-form__group">
        <label class="register-form__label" for="address">住所</label>
        <input class="register-form__input" type="text" name="address" id="address">
      </div>
      <div class="register-form__group">
        <label class="register-form__label" for="building">建物名</label>
        <input class="register-form__input" type="text" name="building" id="building">
        <p class="register-form__error-message">
          @error('building')
          {{ $message }}
          @enderror
        </p>
      </div>
      <input class="register-form__btn btn" type="submit" value="更新する">
    </form>
  </div>
</div>
@endsection('content')