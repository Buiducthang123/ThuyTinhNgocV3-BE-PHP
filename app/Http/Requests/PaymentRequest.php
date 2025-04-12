<?php

namespace App\Http\Requests;

use App\Enums\PaymentType;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'order_id' => 'required|integer|exists:orders,id',
            'user_id' => 'required|integer:exists:users,id',
            'amount' => 'required|numeric',
            'transaction_type' => 'required|string|in:'.implode(',', PaymentType::getValues()),
        ];
    }

    public function messages(){
        return [
            'order_id.required' => 'Mã đơn hàng không được để trống',
            'order_id.integer' => 'Mã đơn hàng phải là số',
            'order_id.exists' => 'Mã đơn hàng không tồn tại',
            'user_id.required' => 'Mã người dùng không được để trống',
            'user_id.integer' => 'Mã người dùng phải là số',
            'user_id.exists' => 'Mã người dùng không tồn tại',
            'amount.required' => 'Số tiền thanh toán không được để trống',
            'amount.numeric' => 'Số tiền thanh toán phải là số',
            'transaction_type.required' => 'Loại giao dịch không được để trống',
            'transaction_type.string' => 'Loại giao dịch phải là chuỗi',
            'transaction_type.in' => 'Loại giao dịch không hợp lệ',
        ];
    }
}
