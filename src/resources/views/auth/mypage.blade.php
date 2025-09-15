@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/mypage.css')}}">
@endsection

@section('content')
<div class="mypage-container">
    <div class="mypage__profile-section">
        <div class="mypage__profile-section-inner">
            <div class="mypage__profile__img">
            @if($auth_user->img_url ===null)
                <div class="mypage__profile__img-default"> </div>
            @else
                <img class="mypage__profile__img-selected" src="{{ asset('storage/' . $auth_user->img_url) }}" alt="{{ $auth_user->name }}">
            @endif
            </div>
            <h2 class="mypage__profile-name">{{ $auth_user->name }}</h2>
        </div>
            <a href="{{ route('mypage.edit') }}" class="mypage__edit-profile-btn">プロフィールを編集</a>
        </div>
    </div>

    <div class="mypage__list">
        <a class="mypage__list__link {{ $page === 'sell' ? 'active' : '' }}" href="{{ route('mypage.show', ['page' => 'sell']) }}">
            出品した商品
        </a>
        <a class="mypage__list__link {{ $page === 'buy' ? 'active' : '' }}" href="{{ route('mypage.show', ['page' => 'buy']) }}">
            購入した商品
        </a>
    </div>

    <div class="mypage__inner">
        @foreach ($products as $product)
            <div class="toppage__product-container">
                <a href="{{ route('item.show', $product['id']) }}" class="toppage__product-image">
                    <img src="{{ asset('storage/' . $product['img_url']) }}" alt="{{ $product['product_name'] }}">
                </a>
                <p class="toppage__product-name">
                    {{ $product['product_name'] }}
                </p>
                @if ($product['buyer_id'] !== null) 
                    <p class="toppage__product-sold">Sold</p>
                @endif
            </div>
         @endforeach
    </div>
@endsection