@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/sell.css')}}">
@endsection

@section('content')
<div class="sell-form-content">
    <h2 class="sell-form__heading content__heading">商品の出品</h2>
    <form>
        <div class="sell-form__image-container">
            <h3>商品画像</h3>
            <div class="sell-form__image__upload-area">
                <label for="img_url" class="custom-file-label">
                    画像を選択する
                </label>
                <input type="file" name="img_url" id="img_url">
            </div>

        <div class="sell-form__detail">
            <p class="sell-form__detail-title">
                商品の詳細    
            </p>
            <h3>カテゴリー</h3>
            <div>
                @foreach($categories as $category)
                    <input type="checkbox" id="cat_{{ $category->id }}" name="category_id" value="{{ $category->id }}">
                    <label for="cat_{{ $category->id }}" class="category-label">
                        {{ $category->content }}
                    </label>
                @endforeach
            </div>

            <h3>商品の状態</h3>
            <select name="condition_id" class="condition" id="conditionSelect">
                <option value="">選択してください</option>
                @foreach($conditions as $condition)
                    <option value="{{ $condition->id }}"> {{ $condition->content }} </option>
                @endforeach
            </select>

            <p class="sell-form__detail-title">
                商品名と説明
            </p>

            <h3>商品名</h3>
            <input>

            <h3>ブランド名</h3>
            <input>

            <h3>商品の説明</h3>
            <textarea name="comment" rows="4" cols="50"></textarea>

            <h3>販売価格</h3>
            <input>
        </div>

        <button class="sell-form__button btn">出品する</button>
    </form>
</div>
@endsection('content')