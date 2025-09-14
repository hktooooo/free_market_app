<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'product_id' => 'required|integer|exists:products,id',
            'payment_method' => 'required|string',
            'zipcode' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払方法を選択してください',
            'zipcode.required' => '配送先を設定してください',
            'zipcode.regex' => '郵便番号はハイフンありの8文字「123-4567」で設定してください',
            'address.required' => '配送先を設定してください',
        ];
    }
}
