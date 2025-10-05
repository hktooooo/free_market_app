@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/sell.css')}}">
@endsection

@section('content')
<div class="sell-form-content">
    <div class="sell-form-content__inner">
        <h2 class="sell-form__heading">商品の出品</h2>
        <form class="sell-form__form" action="{{ route('sell.exec') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="sell-form__image-container">
                <h3 class="sell-form__image-title">商品画像</h3>
                <div class="sell-form__image__upload-area">
                    <label for="img_url" class="custom-file-label">
                        画像を選択する
                    </label>
                    <input type="file" name="img_url" id="img_url">
                    <!-- 選択ファイル名表示 -->
                    <div id="file-name" class="file-name"></div>
                </div>
                <p class="sell-form__error-message">
                    @error('img_url')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="sell-form__detail-first">
                <p class="sell-form__detail-title">
                    商品の詳細
                </p>
                <h3 class="sell-form__detail-title__category">カテゴリー</h3>
                <div class="sell-form__detail__category-selects">
                    @foreach($categories as $category)
                        <input type="checkbox" id="cat_{{ $category->id }}" name="categories[]" value="{{ $category->id }}"
                            {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                        <label for="cat_{{ $category->id }}" class="category-label">
                            {{ $category->content }}
                        </label>
                    @endforeach
                </div>
                <p class="sell-form__error-message">
                    @error('categories')
                    {{ $message }}
                    @enderror
                </p>

                <h3 class="sell-form__detail-title__condition">商品の状態</h3>
                <div class="sell-form__detail__condition-selects">
                    <select name="condition_id" class="condition" id="conditionSelect">
                        <option value="" disabled {{ old('condition_id') ? '' : 'selected' }} hidden>選択してください</option>
                        @foreach($conditions as $condition)
                            <option value="{{ $condition->id }}"
                                {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                                {{ $condition->content }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <p class="sell-form__error-message">
                    @error('condition_id')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="sell-form__detail-second">
                <p class="sell-form__detail-title">
                    商品名と説明
                </p>

                <h3>商品名</h3>
                <div class="sell-form__detail__input">
                    <input class="sell-form__input" type="text" name="product_name" id="product_name" value="{{ old('product_name') }}">
                </div>
                <p class="sell-form__error-message">
                    @error('product_name')
                    {{ $message }}
                    @enderror
                </p>

                <h3>ブランド名</h3>
                <div class="sell-form__detail__input">
                    <input class="sell-form__input" type="text" name="brand" id="brand" value="{{ old('brand') }}">
                </div>

                <h3>商品の説明</h3>
                <div class="sell-form__detail__input">
                    <textarea name="detail">{{ old('detail') }}</textarea>
                </div>
                <p class="sell-form__error-message">
                    @error('detail')
                    {{ $message }}
                    @enderror
                </p>

                <h3>販売価格</h3>
                <div class="sell-form__detail__input input__price-wrap">
                    <input class="sell-form__input" type="text" name="price" id="price" value="{{ old('price') }}">
                </div>
                <p class="sell-form__error-message">
                    @error('price')
                    {{ $message }}
                    @enderror
                </p>            
            </div>

            <button class="sell-form__button btn">出品する</button>
        </form>
    </div>
</div>

<script>
  const input = document.getElementById('img_url');
  const fileNameDisplay = document.getElementById('file-name');

  input.addEventListener('change', function() {
      const file = input.files[0];
      if (file) {
          fileNameDisplay.textContent = file.name;
      } else {
          fileNameDisplay.textContent = '';
      }
  });
</script>

@endsection('content')