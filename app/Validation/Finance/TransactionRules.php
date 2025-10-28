<?php

namespace App\Validation\Finance;

use Illuminate\Validation\Rule;

class TransactionRules
{
    public static function rules($transactionID = null): array
    {
        return [
            'transaction_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'transaction_item_id' => [
                'required',
                'exists:transaction_items,id',
            ],
            'description' => [
                'required',
                'string',
                'max:500',
            ],
            'type' => [
                'required',
                Rule::in(['income', 'expense']),
            ],
            'amount' => [
                'required',
                'regex:/^[0-9.,]+$/', // chỉ cho phép số và dấu . , để người dùng gõ kiểu "100.000"
                'min:1',
            ],
        ];
    }

    public static function messages(): array
    {
        return [
            'transaction_date.required' => 'Chọn ngày giao dịch.',
            'transaction_date.date' => 'Ngày giao dịch không hợp lệ.',
            'transaction_date.before_or_equal' => 'Ngày giao dịch không thể lớn hơn hôm nay.',

            'transaction_item_id.required' => 'Vui lòng chọn hạng mục.',
            'transaction_item_id.exists' => 'Hạng mục được chọn không tồn tại.',

            'description.required' => 'Nhật mô tả.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá 500 ký tự.',

            'type.required' => 'Chọn loại giao dịch (thu hoặc chi).',
            'type.in' => 'Giá trị loại giao dịch không hợp lệ.',

            'amount.required' => 'Vui lòng nhập số tiền.',
            'amount.regex' => 'Số tiền chỉ được chứa ký tự số và dấu phân cách.',
            'amount.min' => 'Số tiền phải lớn hơn 0.',
        ];
    }
}
