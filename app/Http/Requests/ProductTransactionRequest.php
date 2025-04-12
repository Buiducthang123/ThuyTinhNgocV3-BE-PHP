<?php

namespace App\Http\Requests;

use App\Enums\ProductTransactionStatus;
use App\Enums\ProductTransactionType;
use Illuminate\Foundation\Http\FormRequest;

class ProductTransactionRequest extends FormRequest
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
            'product_id' => 'required|integer|exists:products,id',
            'user_id' => 'nullable|integer|exists:users,id',
            'note' => 'string|nullable',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'status' => 'required|in:' . implode(',', ProductTransactionStatus::getValues()),
            'type' => 'required|in:' . implode(',', ProductTransactionType::getValues()),
            'date' => 'required|date',
        ];
        return $rules;
    }

    public function messages(){
        return [
            'product_id.required' => 'ID sản phẩm không được để trống',
            'product_id.integer' => 'ID sản phẩm phải là số',
            'product_id.exists' => 'ID sản phẩm không tồn tại',
            'user_id.integer' => 'ID người dùng phải là số',
            'user_id.exists' => 'ID người dùng không tồn tại',
            'note.string' => 'Ghi chú phải là chuỗi',
            'price.required' => 'Giá không được để trống',
            'price.numeric' => 'Giá phải là số',
            'quantity.required' => 'Số lượng không được để trống',
            'quantity.integer' => 'Số lượng phải là số',
            'status.required' => 'Trạng thái không được để trống',
            'status.in' => 'Trạng thái không hợp lệ',
            'type.required' => 'Loại không được để trống',
            'type.in' => 'Loại không hợp lệ',
            'date.required' => 'Ngày không được để trống',
            'date.date' => 'Ngày phải là ngày',
            'date.date_format' => 'Ngày phải đúng định dạng d-m-Y',
        ];
    }
}
