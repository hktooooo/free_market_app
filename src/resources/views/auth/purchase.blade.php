{{ $product['id'] }}
{{ $product['product_name'] }}
{{ $product->address_purchase }}

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/purchase.css') }}">
@endsection

@section('content')

<form class="purchase-container" action="{{ route('purchase.exec') }}" method="post">
    @csrf        
    <div class="purchase-left">
        <div class="item-info">
            <div class="item-image">
                <img src="{{ asset('storage/' . $product->img_url) }}" alt="{{ $product->product_name }}">
            </div>
            <div class="item-detail">
                <h2 class="item-name">
                    {{ $product->product_name }}
                </h2>
                <p class="item-price">
                    <span>&yen;</span>
                    {{ number_format($product->price) }}
                </p>
            </div>
        </div>
        <div class="payment-section">
            <h3 class="payment-section-title">支払い方法</h3>
            <select name="payment_method" class="payment-select" id="paymentSelect">
                <option value="">選択してください</option>
                @foreach($paymentMethods as $paymentMethod)
                <option value="{{ $paymentMethod->content_name }}"> {{ $paymentMethod->content }} </option>
                @endforeach
            </select>
        </div>
        <div class="address-section">
            <div class="address-section-left">
                <h3 class="addredd-section-title">配送先</h3>
                <div class="address-section-left__text-container">
                    <p class="address-section-left__text">
                        〒 {{ $purchase->zipcode_purchase }}
                    </p>
                    <p class="address-section-left__text">
                        {{ $purchase->address_purchase }}
                    </p>
                    <p class="address-section-left__text">
                        {{ $purchase->building_purchase }}
                    </p>
                </div>
            </div>
            <div class="address-section-right">
                <a href="{{ route('purchase.address', $product['id']) }}" class="change-link">
                    変更する
                </a>
            </div>
        </div>
    </div>

    <div class="purchase-right">
        <div class="purchase__form__summary-box">
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="zipcode" value="{{ $purchase->zipcode_purchase }}">
            <input type="hidden" name="address" value="{{ $purchase->address_purchase }}">
            <input type="hidden" name="building" value="{{ $purchase->building_purchase }}">
            <div class="summary-row">
                <div class="summary-title">商品代金</div>
                <div class="summary-value"><span>&yen; </span>{{ number_format($product->price) }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-title">支払い方法</div>
                <div id="selectedText" class="summary-value"></div>
            </div>
        </div>
        <button class="purchase-button">購入する</button>
        <ul>
            {{-- payment_method のエラー --}}
            @if ($errors->has('payment_method'))
                <li>{{ $errors->first('payment_method') }}</li>
            @endif

            {{-- zipcode と address のどちらかのエラー --}}
            @if ($errors->has('zipcode'))
                <li>{{ $errors->first('zipcode') }}</li>
            @elseif ($errors->has('address'))
                <li>{{ $errors->first('address') }}</li>
            @endif
        </ul>
    </div>
</form>

<script>
    const select = document.getElementById('paymentSelect');
    const selectedText = document.getElementById('selectedText');

    // 初期状態は空文字
    selectedText.textContent = '';

    // selectが変わったときに文字を更新
    select.addEventListener('change', (e) => {
        const value = e.target.value;

        if (value === 'konbini') {
            selectedText.textContent = 'コンビニ払い';
        } else if (value === 'card') {
            selectedText.textContent = 'カード払い';
        } else {
            selectedText.textContent = ' ';
        }
    });
</script>

@endsection