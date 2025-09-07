{{ $userId }}

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
<div class="toppage-content">
    <div class="toppage__list">
        <a class="toppage__list__link-favorite {{ $tab === '' ? 'active' : '' }}" href="{{ route('index.show', ['q' => $q ?? '']) }}">
            おすすめ
        </a>
        <a class="toppage__list__link-mylist {{ $tab === 'mylist' ? 'active' : '' }}" href="{{ route('index.show', ['tab' => 'mylist', 'q' => $q ?? '']) }}">
            マイリスト
        </a>
    </div>
    <div class="toppage__innner">
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
</div>
@endsection('content')