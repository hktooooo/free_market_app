@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/address.css')}}">
@endsection

@section('content')
<div class="address-form">
  <h2 class="address-form__heading content__heading">住所変更</h2>
  <div class="address-form__inner">
    <form class="address-form__form" action="{{ route('address.update') }}" method="post">
      @csrf
      <input type="hidden" name="product_id" value="{{ $product->id }}">
      <div class="address-form__group">
        <label class="address-form__label" for="zipcode">郵便番号</label>
        <input class="address-form__input" type="text" name="zipcode" id="zipcode" value="{{ $purchase->zipcode_purchase }}">
        <p class="address-form__error-message">
          @error('zipcode')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="address-form__group">
        <label class="address-form__label" for="address">住所</label>
        <input class="address-form__input" type="text" name="address" id="address" value="{{ $purchase->address_purchase }}">
        <p class="address-form__error-message">
          @error('address')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="address-form__group">
        <label class="address-form__label" for="building">建物名</label>
        <input class="address-form__input" type="text" name="building" id="building" value="{{ $purchase->building_purchase }}">
        <p class="address-form__error-message">
          @error('building')
          {{ $message }}
          @enderror
        </p>
      </div>
      <input class="address-form__btn btn" type="submit" value="更新する">
    </form>
  </div>
</div>
@endsection('content')