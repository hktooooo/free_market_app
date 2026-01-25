@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/mypage.css')}}">
@endsection

@section('content')
@php
    $rating = round($auth_user->avg_rating) ?? 0;
@endphp

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

            <div class="mypage__profile-name-star">
                <h2 class="mypage__profile-name">{{ $auth_user->name }}</h2>

                @if (!is_null($auth_user->avg_rating))
                    <div class="star-rating">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $rating ? 'rating-active' : '' }}">★</span>
                        @endfor
                    </div>
                @endif
            </div>
        </div>
        <div>
            <a href="{{ route('mypage.edit') }}" class="mypage__edit-profile-btn">プロフィールを編集</a>
        </div>
    </div>

    <div class="mypage__list">
        <div class="mypage__list__inner">
            <a class="mypage__list__link {{ $page === 'sell' ? 'active' : '' }}" href="{{ route('mypage.show', ['page' => 'sell']) }}">
                出品した商品
            </a>
            <a class="mypage__list__link {{ $page === 'buy' ? 'active' : '' }}" href="{{ route('mypage.show', ['page' => 'buy']) }}">
                購入した商品
            </a>
            <div class="mypage__list__link-trading">
                <a class="mypage__list__link {{ $page === 'trading' ? 'active' : '' }}" href="{{ route('mypage.show', ['page' => 'trading']) }}">
                    取引中の商品
                </a>
                @if ($totalUnreadCount > 0)
                    <p class="total-unread-counter">{{ $totalUnreadCount }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="mypage__inner">
        @if ($page !== 'trading')
        {{-- 出品・購入 --}}
            @foreach ($products as $product)
                <div class="toppage__product-container">
                    <a href="{{ route('item.show', $product->id) }}" class="toppage__product-image">
                        <img src="{{ asset('storage/' . $product->img_url) }}" alt="{{ $product->product_name }}">
                    </a>
                    <p class="toppage__product-name">
                        {{ $product->product_name }}
                    </p>
                    @if ($product->buyer_id !== null) 
                        <p class="toppage__product-sold">Sold</p>
                    @endif
                </div>
            @endforeach
        @else
        {{-- 取引中 --}}
            @foreach ($products as $product)
                @php
                    $room = $product->rooms->first();
                @endphp
                <div class="toppage__product-container">
                    <div class="product-image-wrapper">
                        <a href="{{ route('tradechat.show', $room->id) }}" class="toppage__product-image">
                            <img src="{{ asset('storage/' . $product->img_url) }}" alt="{{ $product->product_name }}">
                        </a>
                        @if ($product->unread_count > 0)
                            <p class="product-unread-counter">
                                {{ $product->unread_count }}
                            </p>
                        @endif
                    </div>
                    <p class="toppage__product-name">
                        {{ $product->product_name }}
                    </p>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection