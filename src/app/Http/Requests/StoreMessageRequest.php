<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
            'message' => 'required|string|max:400',
            'image'   => 'nullable|image|mimes:png,jpeg,jpg',
        ];
    }

    /**
     * エラーメッセージ（任意）
     */
    public function messages()
    {
        return [
            'message.required' => '本文を入力してください',
            'message.string'   => '本文は文字列で入力してください',
            'message.max'      => '本文は400文字以内で入力してください',

            'image.image' => '画像ファイルを選択してください',
            'image.mimes' => '「.png」 または「.jpeg」 形式でアップロードしてください',
        ];
    }
}
