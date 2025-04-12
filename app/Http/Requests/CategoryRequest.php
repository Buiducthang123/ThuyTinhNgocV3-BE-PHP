<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $categoryId = $this->route('id');

        $rule = [
            'name' => 'required|max:255',
            'avatar' => 'nullable|url',
            'slug' => 'required|max:255|unique:categories,slug,' . $categoryId,
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable',
            'children' => 'nullable|array',
            'children.*' => 'exists:categories,id',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rule['slug'] = 'required|max:255|unique:categories,slug,' . $categoryId;
        }

        return $rule;
    }

    public function messages(): array{
        return [
            'name.required' => 'Tên danh mục không được để trống',
            'name.max' => 'Tên danh mục không được quá 255 ký tự',
            'avatar.url' => 'Avatar không đúng định dạng',
            'slug.required' => 'Slug không được để trống',
            'slug.max' => 'Slug không được quá 255 ký tự',
            'slug.unique' => 'Slug đã tồn tại',
            'parent_id.exists' => 'Danh mục cha không tồn tại',
            'children.array' => 'Danh mục con phải là mảng',
            'children.*.exists' => 'Danh mục con không tồn tại',
        ];
    }
}
