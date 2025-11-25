<?php

namespace App\Validation\Setting;


class EmailRules
{
    public static function rules($isHavePassword): array
    {
        return [
            'mailer' => 'required|string|in:smtp,sendmail,mailgun,ses,postmark,log',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => ($isHavePassword ? 'nullable' : 'required') . '|string|max:255',
            'encryption' => 'nullable|string|in:tls,ssl',
            'from_address' => 'required|email|max:255',
            'from_name' => 'required|string|max:255',
        ];
    }

    public static function messages(): array
    {
        return [
            'mailer.required' => 'Mailer là bắt buộc.',
            'mailer.in' => 'Mailer không hợp lệ. Chỉ chấp nhận: smtp, sendmail, mailgun, ses, postmark, log.',
            'host.required' => 'Host là bắt buộc.',
            'host.string' => 'Host phải là chuỗi.',
            'host.max' => 'Host không được vượt quá 255 ký tự.',
            'port.required' => 'Port là bắt buộc.',
            'port.integer' => 'Port phải là số nguyên.',
            'port.min' => 'Port phải lớn hơn 0.',
            'port.max' => 'Port không được vượt quá 65535.',
            'username.required' => 'Username là bắt buộc.',
            'username.string' => 'Username phải là chuỗi.',
            'username.max' => 'Username không được vượt quá 255 ký tự.',
            'password.required' => 'Password là bắt buộc.',
            'password.string' => 'Password phải là chuỗi.',
            'password.max' => 'Password không được vượt quá 255 ký tự.',
            'encryption.in' => 'Encryption không hợp lệ. Chỉ chấp nhận: tls, ssl.',
            'from_address.required' => 'Địa chỉ email người gửi là bắt buộc.',
            'from_address.email' => 'Địa chỉ email người gửi không hợp lệ.',
            'from_address.max' => 'Địa chỉ email người gửi không được vượt quá 255 ký tự.',
            'from_name.required' => 'Tên người gửi là bắt buộc.',
            'from_name.string' => 'Tên người gửi phải là chuỗi.',
            'from_name.max' => 'Tên người gửi không được vượt quá 255 ký tự.',
        ];
    }
}
