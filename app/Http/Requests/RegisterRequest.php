<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:255|confirmed',
            'fullName' => 'required|max:255',
        ];

        if(isset($this->companyName) || isset($this->companyAddress) || isset($this->companyPhoneNumber) || isset($this->companyTaxCode) || isset($this->contactPersonName) || isset($this->representativeIdCard) || isset($this->representativeIdCardDate) || isset($this->contactPersonPosition)){
            $rules =  array_merge($rules, [
                'companyName' => 'required|max:255',
                'companyAddress' => 'required|max:255',
                'companyPhoneNumber' => 'required|regex:/^0[0-9]{9}$/',
                'companyTaxCode' => 'required', // Mã số thuế không có định dạng cụ thể
                'contactPersonName' => 'required|max:255',
                'representativeIdCard' => 'required', // Số CMND không có định dạng cụ thể
                'representativeIdCardDate' => 'required|date',
                'contactPersonPosition' => 'required|max:255',
            ]);
        }

        return $rules;
    }

    public function messages(): array{
        return [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max' => 'Mật khẩu không được quá 255 ký tự',
            'password.confirmed' => 'Mật khẩu không khớp',
            'fullName.required' => 'Họ tên không được để trống',
            'fullName.max' => 'Họ tên không được quá 255 ký tự',
            'phoneNumber.required' => 'Số điện thoại không được để trống',
            'phoneNumber.regex' => 'Số điện thoại không đúng định dạng',
            'companyName.required' => 'Tên công ty không được để trống',
            'companyName.max' => 'Tên công ty không được quá 255 ký tự',
            'companyAddress.required' => 'Địa chỉ công ty không được để trống',
            'companyAddress.max' => 'Địa chỉ công ty không được quá 255 ký tự',
            'companyPhoneNumber.required' => 'Số điện thoại công ty không được để trống',
            'companyPhoneNumber.regex' => 'Số điện thoại công ty không đúng định dạng',
            'companyTaxCode.required' => 'Mã số thuế công ty không được để trống',
            'companyTaxCode.regex' => 'Mã số thuế công ty không đúng định dạng',
            'contactPersonName.required' => 'Tên người liên hệ không được để trống',
            'contactPersonName.max' => 'Tên người liên hệ không được quá 255 ký tự',
            'representativeIdCard.required' => 'Số CMND không được để trống',
            'representativeIdCard.regex' => 'Số CMND không đúng định dạng',
        ];
    }
}
