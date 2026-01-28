@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/tradechat.css')}}">
@endsection

@section('hide_search_form')
@endsection

@php
    $completed_flag = $room->is_buyer_completed;
@endphp

@section('content')
<div class="trade-chat-container">
    {{-- サイドバー --}}
    <div class="trade-sidebar">
        <h4>その他の取引</h4>
        <div class="other-trade-item-list">
            @foreach ($otherRooms as $otherRoom)
                <a href="{{ route('tradechat.show', $otherRoom) }}" class="other-trade-item">
                    <div>
                        <p>{{ $otherRoom->product->product_name }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- メイン --}}
    <div class="trade-main-contents">
        {{-- ヘッダー --}}
        <div class="trade-header">
            <div class="partner">
                <div class="partner-img">
                    @if($isBuyer)
                        @if($room->seller->img_url === null)
                            <div class="partner-img-default"> </div>
                        @else
                            <img class="partner-img-selected" src="{{ asset('storage/' . $room->seller->img_url) }}">
                        @endif
                    @else
                        @if($room->buyer->img_url === null)
                            <div class="partner-img-default"> </div>
                        @else
                            <img class="partner-img-selected" src="{{ asset('storage/' . $room->buyer->img_url) }}">
                        @endif
                    @endif
                </div>
                <p class="partner-name">
                    {{ $isBuyer ? $room->seller->name : $room->buyer->name }} さんとの取引画面
                </p>
            </div>
            {{-- 取引完了ボタン --}}
            @if ($isBuyer && !$completed_flag)
                <button id="openModal" class="complete-btn">
                    取引を完了する
                </button>
            @endif
        </div>

        {{-- 商品情報 --}}
        <div class="product-info">
            <img src="{{ asset('storage/' . $room->product->img_url) }}" class="product-image">
            <div class="product-text">
                <h3>{{ $room->product->product_name }}</h3>
                <p>¥{{ number_format($room->product->price) }}</p>
            </div>
        </div>

        {{-- メッセージエリア --}}
        <div class="chat-messages">
            @foreach($messages as $message)
                <div class="message-row 
                    {{ $message->user_id === auth()->id() ? 'my-message' : 'other-message' }}">
                    <div class="message-bubble {{ $message->user_id === auth()->id() ? 'my-message-bubble' : '' }}">
                        <div class="user-info-wrapper {{ $message->user_id === auth()->id() ? 'my-message-user' : '' }}">
                            <div class="user-img">
                                @if($message->sender->img_url === null)
                                    <div class="user-img-default"> </div>
                                @else
                                    <img class="user-img-selected" src="{{ asset('storage/' . $message->sender->img_url) }}" alt="{{ $message->sender->name }}">
                                @endif
                            </div>
                            <div class="user-name">
                                {{ $message->sender->name }}
                            </div>
                        </div>

                        <div class="message-text-box {{ $message->user_id === auth()->id() ? 'my-message' : 'other-message' }}">
                            @if($message->message)
                                <p class="message-text">{{ $message->message }}</p>
                            @endif

                            @if($message->image)
                                <p class="message-img">
                                    <img src="{{ asset('storage/' . $message->image) }}" class="chat-image">
                                </p>
                            @endif
                        </div>

                        {{-- 自分のメッセージのみ編集、削除可能 --}}
                        @if ($message->isMine(auth()->id()))
                            <div class="my-message-editer">
                                <button
                                    class="edit-btn"
                                    data-message-id="{{ $message->id }}"
                                    data-message-text="{{ $message->message }}"
                                    data-action="{{ route('tradechat.message.update', $message) }}"
                                    @if($completed_flag) disabled @endif
                                >
                                    編集
                                </button>
                                <form
                                    action="{{ route('tradechat.message.destroy', $message) }}"
                                    method="POST"
                                    onsubmit="return confirm('このメッセージを削除しますか？');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" @if($completed_flag) disabled @endif>削除</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- 入力フォーム --}}
        <div class="chat-form-wrapper">
            <p class="chat-form__error-message">
                @error('message')
                    {{ $message }}
                @enderror
            </p>
            <p class="chat-form__error-message">
                @error('image')
                    {{ $message }}
                @enderror
            </p>
            <form class="chat-form" id="chat-form" method="POST" action="{{ route('tradechat.message.store', $room) }}" enctype="multipart/form-data">
                @csrf

                <input
                    type="text"
                    name="message"
                    id="message-input"
                    placeholder="取引メッセージを記入してください"
                    value="{{ $errors->any() ? old('message') : '' }}"
                >

                <label class="image-btn" @if($completed_flag) disabled @endif>
                    画像を追加
                    <input
                        type="file"
                        name="image"
                        id="image-input"
                        hidden
                        @if($completed_flag) disabled @endif
                    >
                </label>

                <button type="submit"
                    class="send-btn"
                    @if($completed_flag) disabled @endif>
                </button>
            </form>
        </div>
    </div>
</div>


{{-- モーダル --}}
<div id="modalOverlay" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-content-title">取引が完了しました。</div>

        <div class="rating-main">
            <p>今回の取引相手はどうでしたか？</p>
            <div class="rating" data-user-id="{{ $room->seller_id }}">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="star" data-score="{{ $i }}">★</span>
                @endfor
            </div>
        </div>

        <div class="modal-actions">
            <form method="POST" action="{{ $ratingRoute }}">
                @csrf
                <input type="hidden" name="{{ $isBuyer ? 'seller_rating' : 'buyer_rating' }}" id="rating">
                <button type="submit" class="modal-confirm">
                    送信する
                </button>
            </form>
        </div>
    </div>
</div>

<div id="editModal" class="modal-overlay-edit">
    <div class="modal-content-edit">
        <div class="modal-content-edit-title">メッセージを編集</div>

        <form
            class="chat-form-edit"
            id="editForm"
            method="POST"
            action="{{ old('editing_message_id')
                ? route('tradechat.message.update', old('editing_message_id'))
                : '' }}"
        >
            @csrf
            @method('PUT')

            <input
                type="text"
                name="messageEdit"
                id="editMessage"
                value="{{ old('messageEdit') }}"
            >

            <input
                type="hidden"
                name="editing_message_id"
                value="{{ old('editing_message_id') }}"
            >

            <div class="modal-edit-actions">
                <button type="submit">更新</button>
                <button type="button" id="closeEditModal">キャンセル</button>
            </div>
        </form>

        <p class="chat-form__error-message-edit">
            @error('messageEdit', 'edit')
                {{ $message }}
            @enderror
        </p>
    </div>
</div>


<script>
    const shouldOpenModal = @json(
        $room->is_buyer_completed === 1 && $isSeller === true
    );
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    /* =====================
       枠外クリックで閉じる
    ===================== */
    document.querySelectorAll('.modal-overlay')
        .forEach(overlay => {
            overlay.addEventListener('click', e => {
                if (e.target === overlay) {
                    overlay.style.display = 'none';
                }
            });
        });

    /* =====================
       評価モーダル
    ===================== */
    const openBtn = document.getElementById('openModal');
    const modal = document.getElementById('modalOverlay');
    const hiddenInput = document.getElementById('rating');
    const submitBtn = document.querySelector('.modal-confirm');

    if (modal && typeof shouldOpenModal !== 'undefined' && shouldOpenModal) {
        modal.style.display = 'flex';
    }

    if (openBtn && modal) {
        openBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });
    }

    document.querySelectorAll('.rating').forEach(rating => {
        const stars = rating.querySelectorAll('.star');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const score = star.dataset.score;
                hiddenInput.value = score;

                stars.forEach(s => {
                    s.classList.toggle('active', s.dataset.score <= score);
                });

                submitBtn.disabled = false;
            });
        });
    });

    /* =====================
       編集モーダル
    ===================== */
    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');
    const editMessage = document.getElementById('editMessage');
    const closeBtn = document.getElementById('closeEditModal');

    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            editModal.style.display = 'flex';
            editMessage.value = btn.dataset.messageText;
            editForm.action = btn.dataset.action;

            editForm.querySelector('input[name="editing_message_id"]').value
                = btn.dataset.messageId;
        });
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            editModal.style.display = 'none';
        });
    }

    // エラーの場合開き直し
    if (window.hasEditError) {
    editModal.style.display = 'flex';
    }
});
</script>

<script>
    window.hasEditError = {{ $errors->hasBag('edit') ? 'true' : 'false' }};
</script>
 
<script>
document.addEventListener('DOMContentLoaded', () => {
    const roomId = {{ $room->id }};
    const storageKey = `chat_draft_${roomId}`;

    const input = document.getElementById('message-input');
    const form = document.getElementById('chat-form');

    // localStorage → input 復元（old() 優先）
    if (!input.value) {
        input.value = localStorage.getItem(storageKey) || '';
    }

    // 入力時に保存
    input.addEventListener('input', () => {
        localStorage.setItem(storageKey, input.value);
    });

    // 送信時に下書き削除
    form.addEventListener('submit', () => {
        localStorage.removeItem(storageKey);
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const roomId = {{ $room->id }};
    const storageKey = `chat_draft_${roomId}`;

    const logoutBtn = document.getElementById('logout-btn');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            localStorage.removeItem(storageKey);
        });
    }
});
</script>

@endsection
