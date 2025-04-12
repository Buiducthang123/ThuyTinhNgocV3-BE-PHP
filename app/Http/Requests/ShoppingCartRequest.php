<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShoppingCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(){
        return [
            'user_id.required' => 'User ID là bắt buộc.',
            'user_id.integer' => 'User ID phải là số.',
            'user_id.exists' => 'User ID không tồn tại.',
            'product_id.required' => 'Product ID là bắt buộc.',
            'product_id.integer' => 'Product ID phải là số.',
            'product_id.exists' => 'Product ID không tồn tại.',
            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là số.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
        ];
    }
}
