<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Các dòng ngôn ngữ mặc định cho trình xác thực.
    |
    */

    'accepted' => 'Trường :attribute phải được chấp nhận.',
    'active_url' => 'Trường :attribute không phải là một URL hợp lệ.',
    'after' => 'Trường :attribute phải là một ngày sau ngày :date.',
    'alpha' => 'Trường :attribute chỉ có thể chứa các chữ cái.',
    'confirmed' => 'Xác nhận :attribute không khớp.',
    'current_password' => 'Mật khẩu hiện tại không chính xác.',
    'email' => 'Trường :attribute phải là địa chỉ email hợp lệ.',
    'required' => 'Trường :attribute là bắt buộc.',
    'min' => [
        'string' => 'Trường :attribute phải có ít nhất :min ký tự.',
    ],
    'max' => [
        'string' => 'Trường :attribute không được vượt quá :max ký tự.',
    ],
    'password' => [
        'letters' => 'Mật khẩu phải chứa ít nhất một chữ cái.',
        'mixed' => 'Mật khẩu phải chứa ít nhất một chữ hoa và một chữ thường.',
        'numbers' => 'Mật khẩu phải chứa ít nhất một chữ số.',
        'symbols' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt.',
        'uncompromised' => 'Mật khẩu này đã bị rò rỉ trong một vụ xâm phạm dữ liệu. Vui lòng chọn mật khẩu khác.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Giúp hiển thị tên trường thân thiện hơn cho người dùng.
    |
    */
    'attributes' => [
        'current_password' => 'mật khẩu hiện tại',
        'password' => 'mật khẩu',
        'password_confirmation' => 'xác nhận mật khẩu',
    ],
];
