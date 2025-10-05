<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'img_url' => 'nullable|file|mimes:jpg,jpeg,png',
            'name' => 'required|string|max:20',
            'zipcode' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'img_url.mimes' => 'ファイル拡張子は.jpg .jpeg .pngのいづれかを選択してください',
            'name.required' => 'お名前を入力してください',
            'name.max' => '名前は最大 :max 文字までです',
            'zipcode.required' => '郵便番号を入力してください',
            'zipcode.regex' => '郵便番号はハイフンありの8文字「123-4567」で設定してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
