<?php

namespace App\Validation\Finance;

use Illuminate\Validation\Rule;

class TransactionItemRules
{
    public static function rules($transaction_itemID = null): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('transaction_items', 'title')->ignore($transaction_itemID),
            ],
            'description' => 'nullable'
        ];
    }

    public static function messages(): array
    {
        return [
           'title.required' => 'Nhập tên hạng mục.',
            'title.string' => 'Tên hạng mục không hợp lệ.',
            'title.max' => 'Tên hạng mục không được vượt quá 255 ký tự.',
            'title.unique' => 'Tên hạng mục chi này đã tồn tại.',
        ];
    }
}
