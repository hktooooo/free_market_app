@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css')}}">
@endsection

@section('content')
<div class="item__detail-content">
    <div class="item__detail-image-container">
        <div class="item__detail-image">
            <img src="{{ asset('storage/' . $product['img_url']) }}" alt="{{ $product['product_name'] }}">
        </div>
    </div>
    <div class="item__detail-text">
        <div class="item__detail-name">
            {{ $product['product_name'] }}
        </div>
        <div class="item__detail-brand">
            {{ $product['brand'] }}
        </div>
        <div class="item__detail-price">
            &yen;<span>{{ number_format($product['price']) }}</span>(税込)
        </div>
        <div class="item__detail-price">
        </div>
        <div>
            <input class="item__detail-purchase__btn btn" type="submit" value="購入手続きへ">
        </div>
        <div>
            <div>商品説明</div>
            <div>カラー：グレー</div>
            <div>新品</div>
            <div>商品の状態は良好です。傷もありません。</div>
            <div>購入後、即発送いたします。</div>
        </div>
        <div>
            <div>商品の情報</div>
            <div>カテゴリー</div>
            <div>商品の状態</div>
            <div>{{ $product['condition']['content'] }}</div>
        </div>
        <div>
            <div>コメント(1)</div>
            <div>admin</div>
            <div>こちらにコメントが入ります。</div>
            <form>
                <div>商品へのコメント</div>
                <textarea></textarea>
                <input class="item__detail-comment__btn btn" type="submit" value="コメントを送信する">
            </form>
        </div>

    </div>
    </div>
</div>
@endsection('content')