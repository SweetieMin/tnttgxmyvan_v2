<?php

namespace App\Validation\Finance;

use Illuminate\Validation\Rule;

class TransactionItemRules
{
    public static function rules($transaction_itemID = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('transaction_items', 'name')->ignore($transaction_itemID),
            ],
            'description' => 'nullable'
        ];
    }

    public static function messages(): array
    {
        return [
           'name.required' => 'Nhập tên hạng mục.',
            'name.string' => 'Tên hạng mục không hợp lệ.',
            'name.max' => 'Tên hạng mục không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên hạng mục chi này đã tồn tại.',
        ];
    }
}
