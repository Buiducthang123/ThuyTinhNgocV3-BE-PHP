<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingAddressRequest extends FormRequest
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
            'receiver_name' => 'required|string|max:255',
            'receiver_phone_number' => 'required|string|regex:/^0\d{9}$/', // Kiểm tra số điện thoại Việt Nam
            'province' => 'required|array',
            'province.ProvinceID' => 'required|integer',
            'province.ProvinceName' => 'required|string|max:255',
            'province.NameExtension' => 'required|array',
            'province.IsEnable' => 'required|boolean',
            'district' => 'required|array',
            'district.DistrictID' => 'required|integer',
            'district.DistrictName' => 'required|string|max:255',
            'district.NameExtension' => 'required|array',
            'district.IsEnable' => 'required|boolean',
            'ward' => 'required|array',
            'ward.WardCode' => 'required|string|max:10',
            'ward.WardName' => 'required|string|max:255',
            'ward.NameExtension' => 'required|array',
            // 'ward.IsEnable' => 'required',
            'specific_address' => 'required|string|max:255',
            'is_default' => 'required|boolean',
        ];
    }

    public function messages(){
        return [
            'receiver_name.required' => 'Tên người nhận là bắt buộc.',
            'receiver_phone_number.required' => 'Số điện thoại người nhận là bắt buộc.',
            'receiver_phone_number.regex' => 'Số điện thoại không đúng định dạng.',
            'province.required' => 'Thông tin tỉnh/thành phố là bắt buộc.',
            'province.ProvinceID.required' => 'Mã tỉnh là bắt buộc.',
            'province.ProvinceName.required' => 'Tên tỉnh là bắt buộc.',
            'province.NameExtension.required' => 'Danh sách mở rộng của tỉnh là bắt buộc.',
            'district.required' => 'Thông tin quận/huyện là bắt buộc.',
            'district.DistrictID.required' => 'Mã huyện là bắt buộc.',
            'district.DistrictName.required' => 'Tên huyện là bắt buộc.',
            'ward.required' => 'Thông tin phường/xã là bắt buộc.',
            'ward.WardCode.required' => 'Mã xã/phường là bắt buộc.',
            'specific_address.required' => 'Địa chỉ cụ thể là bắt buộc.',
            'is_default.required' => 'Trạng thái mặc định là bắt buộc.',
        ];
    }
}
