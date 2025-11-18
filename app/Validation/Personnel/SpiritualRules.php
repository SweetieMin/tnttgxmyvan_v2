<?php

namespace App\Validation\Personnel;

use Illuminate\Validation\Rule;

class SpiritualRules
{
    public static function rules($spiritualID = null): array
    {
        return [
            'christian_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'position' => 'required|integer',
            'birthday' => 'required|date',
            'status_login' => 'required|in:active,locked,inactive',
            'phone' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'bio' => 'nullable|string|max:255',
        ];
    }

    public static function messages(): array
    {
        return [
            'christian_name.required' => 'Vui lòng nhập tên thánh.',
            'christian_name.string'   => 'Tên thánh phải là chuỗi ký tự.',
            'christian_name.max'      => 'Tên thánh không được vượt quá 255 ký tự.',

            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.string'   => 'Họ và tên phải là chuỗi ký tự.',
            'name.max'      => 'Họ và tên không được vượt quá 255 ký tự.',

            'gender.required' => 'Vui lòng chọn giới tính.',

            'position.required' => 'Vui lòng chọn chức vụ.',
            

            'birthday.required' => 'Vui lòng chọn ngày sinh.',
            'birthday.date'     => 'Ngày sinh không hợp lệ.',

            'status_login.required' => 'Vui lòng chọn trạng thái đăng nhập.',
            'status_login.in'       => 'Trạng thái đăng nhập không hợp lệ.',

            'phone.string'   => 'Số điện thoại không hợp lệ.',
            'phone.max'      => 'Số điện thoại không được vượt quá 255 ký tự.',

            'address.required' => 'Vui lòng nhập địa chỉ.',
            'address.string'   => 'Địa chỉ phải là chuỗi ký tự.',
            'address.max'      => 'Địa chỉ không được vượt quá 255 ký tự.',

            'email.email' => 'Email không hợp lệ.',
            'email.max'   => 'Email không được vượt quá 255 ký tự.',

            'bio.string' => 'Giới thiệu phải là chuỗi ký tự.',
            'bio.max'    => 'Giới thiệu không được vượt quá 255 ký tự.',
        ];
    }
}
