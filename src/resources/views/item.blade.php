@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css')}}">
@endsection

@section('content')
<div class="item__detail-content">
    <div class="item__detail-image-container">
        <div class="item__detail-image">
            <img src="{{ asset('storage/' . $product->img_url) }}" alt="{{ $product->product_name }}">
        </div>
    </div>
    <div class="item__detail-text">
        <h2 class="item__detail-name">
            {{ $product->product_name }}
        </h2>
        <div class="item__detail-brand">
            {{ $product->brand }}
        </div>
        <div class="item__detail-price">
            &yen;<span>{{ number_format($product->price) }}</span>(税込)
        </div>
        <div class="item__detail-favorites-comments">
            <div class="item__detail-favorites-comments__icon-box">
                <img src="{{ asset('storage/star.png') }}" alt="star">
                <p>{{ $favorite_count }}</p>
            </div>
            <!-- 吹き出しマーク -->
            <div class="item__detail-favorites-comments__icon-box">
                <img src="{{ asset('storage/comment.png') }}" alt="star">
                <p>{{ $comments_count }}</p>
            </div>
        </div>
        <a href="{{ route('purchase.confirm', $product->id) }}" class="item__detail-purchase__btn btn">購入手続きへ</a>
        <div class="item__detail-inner">
            <h3 class="item__detail-inner-title">商品説明</h3>
            <div class="item__detail-inner-text">
                {{ $product->detail }}
            </div>
            <h3 class="item__detail-inner-title">商品の情報</h3>
            <div class="item__detail-inner-category">
                <h4 class="item__detail-inner-category-title">カテゴリー</h4>
                <ul class="item__detail-inner-category-contents">
                    @foreach ($categories as $category)
                    <li>{{ $category }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="item__detail-inner-condition">
                <h4 class="item__detail-inner-condition-title">商品の状態</h4>
                <div class="item__detail-inner-condition-contents">
                    {{ $product->condition->content }}
                </div>
            </div>
        </div>
        <div class="item__comment">
            <div class="item__comment-title" >コメント({{ $comments_count }})</div>
            <div class="item__comment-contents" >
                @foreach ($comments as $comment)
                <div class="item__comment__users-info">
                    <div class="item__comment__users-img">
                        @if($comment->user->img_url === null)
                        <div class="item__comment__users-img-default"> </div>
                        @else
                        <img src="{{ asset('storage/' . $comment->user->img_url) }}" alt="{{ $comment->user->name }}">
                        @endif
                    </div>
                    <div class="item__comment__users-name">
                        {{ $comment->user->name }}
                    </div>
                </div>
                <p class="item__comment__users-comement">
                    {{ $comment->comment }}
                </p>
                @endforeach
            </div>
            <form class="item__comment-form" action="{{ route('comments.store') }}" method="POST">
                @csrf
                <div class="item__comment-form-textarea">
                    <label class="item__comment-form-title" for="comment">商品へのコメント</label>
                    <textarea class="item__comment-form-content" name="comment" id="comment"></textarea>
                    <p class="comment__error-message">
                        @error('comment')
                        {{ $message }}
                        @enderror
                    </p>
                </div>
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <input class="item__detail-comment__btn btn" type="submit" value="コメントを送信する">
            </form>
        </div>
    </div>
    </div>
</div>
@endsection('content')