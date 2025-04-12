<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $productId = $this->route('id');

        $rules = [
            'category_id' => 'required|exists:categories,id',
            'promotion_id' => 'nullable|exists:promotions,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $productId,
            'cover_image' => 'nullable|url|max:255',
            'thumbnail' => 'nullable|array',
            'thumbnail.*' => 'url',
            'description' => 'nullable|string',
            'is_sale' => 'boolean',
            'price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'weight' => 'nullable|numeric|min:0',
            'dimension_length' => 'nullable|numeric|min:0',
            'dimension_width' => 'nullable|numeric|min:0',
        ];

        return $rules;
    }


    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'numeric' => ':attribute phải là số.',
            'integer' => ':attribute phải là số nguyên.',
            'date' => ':attribute không đúng định dạng ngày.',
            'url' => ':attribute không đúng định dạng URL.',
            'max' => ':attribute không được quá :max ký tự.',
            'min' => ':attribute phải lớn hơn hoặc bằng :min.',
            'boolean' => ':attribute phải là true hoặc false.',
            'unique' => ':attribute đã tồn tại.',
            'array' => ':attribute phải là mảng.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'Danh mục',
            'promotion_id' => 'Khuyến mãi',
            'title' => 'Tên sản phẩm',
            'slug' => 'Slug',
            'cover_image' => 'Ảnh bìa',
            'thumbnail' => 'Ảnh minh họa',
            'description' => 'Mô tả',
            'is_sale' => 'Trạng thái bán',
            'price' => 'Giá bán',
            'discount' => 'Giảm giá',
            'weight' => 'Trọng lượng',
            'dimension_length' => 'Chiều dài',
            'dimension_width' => 'Chiều rộng',
        ];
    }
}
