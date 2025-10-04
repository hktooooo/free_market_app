<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'comment' => 'required|string|max:255', // コメント必須・文字列・255文字以内
            'product_id' => 'required|integer|exists:products,id',
        ];
    }

    public function messages()
    {
        return [
            'comment.required' => 'コメントを入力してください',
            'comment.max' => 'コメントは最大 :max 文字までです',
        ];
    }
}
