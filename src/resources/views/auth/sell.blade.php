@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/sell.css')}}">
@endsection

@section('content')
<div class="sell-form-content">
    <div class="sell-form-content__inner">
        <h2 class="sell-form__heading content__heading">商品の出品</h2>
        <form class="sell-form__form" action="{{ route('sell.exec') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="sell-form__image-container">
                <h3>商品画像</h3>
                <div class="sell-form__image__upload-area">
                    <label for="img_url" class="custom-file-label">
                        画像を選択する
                    </label>
                    <input type="file" name="img_url" id="img_url">
                </div>

            <div class="sell-form__detail-first">
                <p class="sell-form__detail-title">
                    商品の詳細
                </p>
                <h3 class="sell-form__detail-title__category">カテゴリー</h3>
                <div class="sell-form__detail__category-selects">
                    @foreach($categories as $category)
                        <input type="checkbox" id="cat_{{ $category->id }}" name="categories[]" value="{{ $category->id }}">
                        <label for="cat_{{ $category->id }}" class="category-label">
                            {{ $category->content }}
                        </label>
                    @endforeach
                </div>

                <h3 class="sell-form__detail-title__condition">商品の状態</h3>
                <div class="sell-form__detail__condition-selects">
                    <select name="condition_id" class="condition" id="conditionSelect">
                        <option value="">選択してください</option>
                        @foreach($conditions as $condition)
                            <option value="{{ $condition->id }}"> {{ $condition->content }} </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="sell-form__detail-second">
                <p class="sell-form__detail-title">
                    商品名と説明
                </p>

                <h3>商品名</h3>
                <div class="sell-form__detail__input">
                    <input class="sell-form__input" type="text" name="product_name" id="product_name">
                </div>

                <h3>ブランド名</h3>
                <div class="sell-form__detail__input">
                    <input class="sell-form__input" type="text" name="brand" id="brand">
                </div>

                <h3>商品の説明</h3>
                <div class="sell-form__detail__input">
                    <textarea name="detail" rows="4" cols="50"></textarea>
                </div>

                <h3>販売価格</h3>
                <div class="sell-form__detail__input input__price-wrap">
                    <input class="sell-form__input" type="text" name="price" id="price">
                </div>
            </div>

            <button class="sell-form__button btn">出品する</button>
        </form>
    </div>
</div>
@endsection('content')