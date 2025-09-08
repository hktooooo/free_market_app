{{ $product['id'] }}
{{ $product['product_name'] }}

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    <div class="purchase-left">
        <div class="item-info">
            <div class="item-image">商品画像</div>
            <div class="item-detail">
                <h2 class="item-name">商品名</h2>
                <p class="item-price"><span>&yen;</span>47,000</p>
            </div>
        </div>
        <div class="payment-section">
            <h3>支払い方法</h3>
            <select name="payment" class="payment-select">
                <option value="">選択してください</option>
                <option value="convenience">コンビニ払い</option>
                <option value="credit">クレジットカード</option>
                <option value="bank">銀行振込</option>
            </select>
        </div>

        <hr>

        <div class="address-section">
            <h3>配送先</h3>
            <p>
                〒 XXX-YYYY <br>
                ここには住所と建物が入ります
            </p>
            <a href="#" class="change-link">変更する</a>
        </div>

        <hr>
    </div>

    <div class="purchase-right">
        <div class="summary-box">
            <div class="summary-row">
                <span>商品代金</span>
                <span>¥47,000</span>
            </div>
            <div class="summary-row">
                <span>支払い方法</span>
                <span>コンビニ払い</span>
            </div>
        </div>

        <button class="purchase-button">購入する</button>
    </div>
</div>
@endsection