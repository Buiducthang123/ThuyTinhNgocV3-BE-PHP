<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
        $rules = [
            'shipping_address' => 'required',
            'note' => 'nullable|string',
        ];

        if($this->isMethod('put') || $this->isMethod('patch')){
           $rules['status'] = 'required|in: ' . implode(',', OrderStatus::getAllStatuses());

           unset($rules['shipping_address']);

           unset($rules['note']);
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'status' => 'Trạng thái',
            'total_amount' => 'Tổng tiền',
            'shipping_fee' => 'Phí vận chuyển',
            'discount_amount' => 'Số tiền giảm giá',
            'final_amount' => 'Số tiền cần thanh toán',
            'payment_method' => 'Phương thức thanh toán',
            'payment_date' => 'Ngày thanh toán',
            'voucher_code' => 'Mã giảm giá',
            'transaction_id' => 'Mã giao dịch',
            'ref_id' => 'Mã giao dịch hoàn',
            'note' => 'Ghi chú',
            'shipping_address' => 'Địa chỉ nhận hàng',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'numeric' => ':attribute phải là số',
            'in' => ':attribute không hợp lệ',
            'date' => ':attribute không hợp lệ',
            'max' => ':attribute không được vượt quá :max ký tự',
        ];
    }
}
