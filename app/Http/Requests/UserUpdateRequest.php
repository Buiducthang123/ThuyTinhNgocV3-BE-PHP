<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $userId = $this->route('userId');

        $rules = [
            'email' => 'email|unique:users,email,' . $userId,
            'password' => 'nullable|min:6|max:255|confirmed',
            'fullName' => 'max:255',
        ];

        if(isset($this->companyName) || isset($this->companyAddress) || isset($this->companyPhoneNumber) || isset($this->companyTaxCode) || isset($this->contactPersonName) || isset($this->representativeIdCard) || isset($this->representativeIdCardDate) || isset($this->contactPersonPosition)){
            $rules =  array_merge($rules, [
                'companyName' => 'max:255',
                'companyAddress' => 'max:255',
                'companyPhoneNumber' => 'regex:/^0[0-9]{9}$/',
                'companyTaxCode' => 'regex:/^[0-9]{10}$/', // Mã số thuế có 10 chữ số // ví dụ: 1234567890
                'contactPersonName' => 'max:255',
                'representativeIdCard' => 'regex:/^[0-9]{9}$/', // Số CMND có 9 chữ số // ví dụ: 123456789
                'representativeIdCardDate' => 'date',
                'contactPersonPosition' => 'max:255',
            ]);
        }

        return $rules;
    }

    public function messages(): array{
        return [
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max' => 'Mật khẩu không được quá 255 ký tự',
            'password.confirmed' => 'Mật khẩu không khớp',
            'fullName.max' => 'Họ tên không được quá 255 ký tự',
            'phoneNumber.regex' => 'Số điện thoại không đúng định dạng',
            'companyName.max' => 'Tên công ty không được quá 255 ký tự',
            'companyAddress.max' => 'Địa chỉ công ty không được quá 255 ký tự',
            'companyPhoneNumber.regex' => 'Số điện thoại công ty không đúng định dạng',
            'companyTaxCode.regex' => 'Mã số thuế công ty không đúng định dạng',
            'contactPersonName.max' => 'Tên người liên hệ không được quá 255 ký tự',
            'representativeIdCard.regex' => 'Số CMND không đúng định dạng',
        ];
    }
}
