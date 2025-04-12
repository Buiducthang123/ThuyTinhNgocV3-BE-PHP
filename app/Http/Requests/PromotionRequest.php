<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromotionRequest extends FormRequest
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
        $promotionId= $this->route('id');

        $rules = [
            'title' => 'required',
            'slug' => 'required|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            'discount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'image' => 'string|required',
            'items'=> 'array|exists:products,id',
            'description' => 'string',

        ];

        if($this->method() == 'PUT'|| $this->method() == 'PATCH'){
            $rules['slug'] = 'required|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:promotions,slug,'.$promotionId;
        }

        return $rules;
    }

    public function messages(){
        return [
            'title.required' => 'Tiêu đề không được để trống',
            'slug.required' => 'Slug không được để trống',
            'slug.regex' => 'Slug không hợp lệ',
            'discount.required' => 'Giảm giá không được để trống',
            'discount.numeric' => 'Giảm giá phải là số',
            'start_date.required' => 'Ngày bắt đầu không được để trống',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ',
            'end_date.required' => 'Ngày kết thúc không được để trống',
            'end_date.date' => 'Ngày kết thúc không hợp lệ',
            'image.required' => 'Ảnh không được để trống',
            'image.string' => 'Ảnh không hợp lệ',
            'description.string' => 'Mô tả không hợp lệ',
            'items.array' => 'Sản phẩm áp dụng không hợp lệ',
            'items.exists' => 'Sản phẩm áp dụng không tồn tại',
        ];
    }
}
