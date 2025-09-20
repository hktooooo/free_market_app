@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/veri-email.css')}}">
@endsection

@section('content')
<div class="register-form">
  <div class="register-form__inner">
    <p>
      登録していただいたメールアドレスに認証メールを送付しました。
      メール認証を完了してください。
    </p>

    <button>認証はこちらから</button>

  @if (session('status') == 'verification-link-sent')
      <p class="success-message">新しい確認リンクを送信しました。</p>
  @endif

  <form method="POST" action="{{ route('verification.send') }}">
      @csrf
      <button type="submit">認証メールを再送する</button>
  </form>
  </div>
</div>
@endsection('content')