<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name' => 'required|string|max:255',
            'detail' => 'required|string|max:255',
            'img_url' => 'required|file|mimes:jpg,jpeg,png',
            'categories' => 'required|array|min:1',
            'categories.*' => 'integer|exists:categories,id',
            'condition_id' => 'required|integer',
            'price' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => '商品名を入力してください',
            'product_name.max' => '商品名は最大 :max 文字までです',
            'detail.required' => '商品の説明を入力してください',
            'detail.max' => '商品の説明は最大 :max 文字までです',
            'img_url.required' => '商品画像を選択してください',
            'img_url.mimes' => 'ファイル拡張子は.jpg .jpeg .pngのいづれかを選択してください',
            'categories.required' => 'カテゴリーを選択してください',
            'categories.array' => 'カテゴリーは配列で指定してください',
            'categories.*.integer' => 'カテゴリーは数値で指定してください',
            'categories.*.exists' => '選択されたカテゴリーが存在しません',
            'condition_id.required' => '商品の状態を選択してください',
            'price.required' => '販売価格を入力してください',
            'price.integer' => '販売価格は数値で入力してください',
            'price.min' => '販売価格は :min円以上で入力してください',
        ];
    }
}
