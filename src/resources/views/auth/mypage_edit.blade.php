@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/mypage_edit.css')}}">
@endsection

@section('content')
<div class="mypage__edit-form">
  <h2 class="mypage__edit-form__heading content__heading">プロフィール設定</h2>
  <div class="mypage__edit-form__inner">
    <form class="mypage__edit-form__form" action="{{ route('mypage.update') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="mypage__edit-form__img-box">
        <div class="mypage__edit-form__img">
          @if($auth_user->img_url ===null)
            <div class="mypage__edit-form__img-default"> </div>
          @else
            <img class="mypage__edit-form__img-selected" src="{{ asset('storage/' . $auth_user->img_url) }}" alt="{{ $auth_user->name }}">
          @endif
        </div>
        <div class="mypage__edit-form__button-area">
          <label for="img_url" class="custom-file-label">
            画像を選択する
          </label>
          <input type="file" name="img_url" id="img_url">

          <!-- 選択ファイル名表示 -->
          <div id="file-name" class="file-name"></div>
        </div>    
      </div>
      <div class="mypage__edit-form__group">
        <label class="mypage__edit-form__label" for="name">ユーザ名</label>
        <input class="mypage__edit-form__input" type="text" name="name" id="name" value= {{ $auth_user->name }}>
        <p class="mypage__edit-form__error-message">
          @error('name')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="mypage__edit-form__group">
        <label class="mypage__edit-form__label" for="zipcode">郵便番号</label>
        <input class="mypage__edit-form__input" type="text" name="zipcode" id="zipcode" value= {{ $auth_user->zipcode }}>
        <p class="mypage__edit-form__error-message">
          @error('zipcode')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="mypage__edit-form__group">
        <label class="mypage__edit-form__label" for="address">住所</label>
        <input class="mypage__edit-form__input" type="text" name="address" id="address" value= {{ $auth_user->address }}>
      </div>
      <div class="mypage__edit-form__group">
        <label class="mypage__edit-form__label" for="building">建物名</label>
        <input class="mypage__edit-form__input" type="text" name="building" id="building" value= {{ $auth_user->building }}>
        <p class="mypage__edit-form__error-message">
          @error('building')
          {{ $message }}
          @enderror
        </p>
      </div>
      <button class="mypage__edit-form__btn btn">更新する</button>
    </form>
  </div>
</div>

<script>
  const input = document.getElementById('img_url');
  const fileNameDisplay = document.getElementById('file-name');

  input.addEventListener('change', function() {
      const file = input.files[0];
      if (file) {
          fileNameDisplay.textContent = file.name;
      } else {
          fileNameDisplay.textContent = '';
      }
  });
</script>

@endsection('content')