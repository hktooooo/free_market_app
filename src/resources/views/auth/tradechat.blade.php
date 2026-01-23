@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/tradechat.css')}}">
@endsection

@section('content')
<div class="trade-chat-container">

    {{-- ヘッダー --}}
    <div class="trade-header">
        <div class="partner">
            {{ $isBuyer ? $room->seller->name : $room->buyer->name }} さんとの取引画面
        </div>
        {{-- 取引完了ボタン --}}
        <button id="openModal" class="complete-btn">
            取引を完了する
        </button>
    </div>

    {{-- 商品情報 --}}
    <div class="product-info">
        <img src="{{ asset('storage/' . $room->product->image) }}" class="product-image">
        <div>
            <h3>{{ $room->product->name }}</h3>
            <p>¥{{ number_format($room->product->price) }}</p>
        </div>
    </div>

    {{-- メッセージエリア --}}
    <div class="chat-messages">
        @foreach($messages as $message)
            <div class="message-row 
                {{ $message->user_id === auth()->id() ? 'my-message' : 'other-message' }}">
                
                <div class="message-bubble">
                    <div class="user-name">
                        {{ $message->sender->name }}
                    </div>

                    @if($message->message)
                        <p>{{ $message->message }}</p>
                    @endif

                    @if($message->image)
                        <img src="{{ asset('storage/' . $message->image) }}" class="chat-image">
                    @endif

                    {{-- 自分のメッセージのみ削除可能 --}}
                    @if ($message->isMine(auth()->id()))
                        <form
                            action="{{ route('tradechat.message.destroy', $message) }}"
                            method="POST"
                            onsubmit="return confirm('このメッセージを削除しますか？');"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit">削除</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- 入力フォーム --}}
    <form class="chat-form" method="POST" action="{{ route('tradechat.message.store', $room) }}" enctype="multipart/form-data">
        @csrf

        <input type="text" name="message" placeholder="取引メッセージを記入してください">

        <label class="image-btn">
            画像を追加
            <input type="file" name="image" hidden>
        </label>

        <button type="submit" class="send-btn">▶</button>
    </form>

    {{-- モーダル --}}
    <div id="modalOverlay" class="modal-overlay">
        <div class="modal-content">
            <h3>取引が完了しました。</h3>

            <p>今回の取引相手はどうでしたか？</p>

            <div class="rating" data-user-id="{{ $room->seller_id }}">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="star" data-score="{{ $i }}">★</span>
                @endfor
            </div>

            <div class="modal-actions">
                <form method="POST" action="{{ route('rating.store', $room) }}">
                    @csrf
                    <input type="hidden" name="seller_rating" id="seller_rating">
                    <button type="submit" class="modal-confirm">
                        送信する
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const openBtn = document.getElementById('openModal');
    const modal = document.getElementById('modalOverlay');
    const hiddenInput = document.getElementById('seller_rating');

    openBtn.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    // 背景クリックで閉じる
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // ⭐ 評価処理
    document.querySelectorAll('.rating').forEach(rating => {
        const stars = rating.querySelectorAll('.star');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const score = star.dataset.score;

                // hidden に値をセット
                hiddenInput.value = score;

                // ★の色変更
                stars.forEach(s => {
                    s.classList.toggle('active', s.dataset.score <= score);
                });

                // 送信ボタン有効化
                submitBtn.disabled = false;
            });
        });
    });
</script>

@endsection
