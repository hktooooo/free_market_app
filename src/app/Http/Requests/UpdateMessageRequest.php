<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMessageRequest extends FormRequest
{
    protected $errorBag = 'edit';

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'messageEdit' => 'required|string|max:400',
        ];
    }

    public function messages()
    {
        return [
            'messageEdit.required' => '本文を入力してください',
            'messageEdit.max'      => '本文は400文字以内で入力してください',
        ];
    }
}
