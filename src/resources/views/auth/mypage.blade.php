@extends('layouts.app')

@section('content')
<div class="mypage-container">
    <div class="profile-section">
        <div class="profile-icon"></div>
        <div class="profile-info">
            <h2 class="username">ユーザー名</h2>
            <a href="#" class="edit-profile-btn">プロフィールを編集</a>
        </div>
    </div>

    <div class="tab-section">
        <a href="#" class="tab active">出品した商品</a>
        <a href="#" class="tab">購入した商品</a>
    </div>

    @foreach ($products as $product)
        @if ($userId === $product['seller_id'])     {{-- 出品した商品を非表示 --}}
            @continue
        @endif

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