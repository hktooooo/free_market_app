<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>フリマアプリ</title>
  <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
  <link rel="stylesheet" href="{{ asset('css/common.css')}}">
  @yield('css')
</head>

<body>
  <div class="app">
    <header class="header">
      <h1 class="header__heading">
        <img src="{{ asset('storage/logo.svg') }}" alt="COACHTECH">
      </h1>
      <form class="search-form" action="{{ route('index.show') }}" method="get">
        <input class="search-form__input" type="text" name="q" id="" value="{{ $q ?? '' }}" placeholder="なにをお探しですか？">
        <input type="hidden" name="tab" value="{{ $tab ?? '' }}">
      </form>
      <nav>
        <ul class="header-nav">
          <li class="header-nav__item">
            @if (Auth::check())
              <form action="/logout" method="post">
                @csrf
                <button class="header-nav__link-logout">ログアウト</button>
              </form>
            @else
              <a class="header-nav__link-login" href="/login">ログイン</a>
            @endif
          </li>
          <li class="header-nav__item">
            <form action="{{ route('mypage.show') }}" method="get">
              <button type="submit" class="header-nav__link-mypage">
                マイページ
              </button>
            </form>
          </li>
          <li class="header-nav__item__button">
            <a class="" href="">出品</a>
          </li>
        </ul>
      </nav>
    </header>
    <div class="content">
      @yield('content')
    </div>
  </div>
</body>

</html>