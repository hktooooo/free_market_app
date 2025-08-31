@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
<div class="toppage-content">
    <div class="toppage__list">
        <a class="toppage__list__link-favorite" href="">おすすめ</a>
        <a class="toppage__list__link-mylist" href="">マイリスト</a>
    </div>
    <div class="toppage__innner">
        <div class="toppage__product-container">
            <div class="toppage__product-image">
                画像
            </div>
            <p class="toppage__product-name">
                商品名
            </p>
        </div>
        <div class="toppage__product-container">
            <div class="toppage__product-image">
                画像
            </div>
            <p class="toppage__product-name">
                商品名
            </p>
        </div>
        <div class="toppage__product-container">
            <div class="toppage__product-image">
                画像
            </div>
            <p class="toppage__product-name">
                商品名
            </p>
        </div>
        <div class="toppage__product-container">
            <div class="toppage__product-image">
                画像
            </div>
            <p class="toppage__product-name">
                商品名
            </p>
        </div>
        <div class="toppage__product-container">
            <div class="toppage__product-image">
                画像
            </div>
            <p class="toppage__product-name">
                商品名
            </p>
        </div>
        <div class="toppage__product-container">
            <div class="toppage__product-image">
                画像
            </div>
            <p class="toppage__product-name">
                商品名
            </p>
        </div>
        <div class="toppage__product-container">
            <div class="toppage__product-image">
                画像
            </div>
            <p class="toppage__product-name">
                商品名
            </p>
        </div>
    </div>
</div>
@endsection('content')