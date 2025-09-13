{{ $product['id'] }}
{{ $product['product_name'] }}
{{ $product->address_purchase }}

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    <div class="purchase-left">
        <div class="item-info">
            <div class="item-image">
                <img src="{{ asset('storage/' . $product->img_url) }}" alt="{{ $product->product_name }}">
            </div>
            <div class="item-detail">
                <h2 class="item-name">{{ $product->product_name }}</h2>
                <p class="item-price"><span>&yen;</span>{{ number_format($product->price) }}</p>
            </div>
        </div>
        <div class="payment-section">
            <h3>支払い方法</h3>
            <select name="payment" class="payment-select">
                <option value="">選択してください</option>
                @foreach($paymentMethods as $paymentMethod)
                <option value="{{ $paymentMethod->id }}"> {{ $paymentMethod->content }} </option>
                @endforeach
            </select>
        </div>

        <hr>

        <div class="address-section">
            <h3>配送先</h3>
            <p>
                〒 {{ $product->zipcode_purchase }}<br>
                {{ $product->address_purchase }}<br>
                {{ $product->building_purchase }}
            </p>
            <a href="{{ route('purchase.address', $product['id']) }}" class="change-link">変更する</a>
        </div>

        <hr>
    </div>

    <div class="purchase-right">
        <div class="summary-box">
            <div class="summary-row">
                <span>商品代金</span>
                <span><span>&yen;</span>{{ number_format($product->price) }}</span>
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