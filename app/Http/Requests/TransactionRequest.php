<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'transaction_date' => 'required|date',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf|max:10240', // Max 10MB per file
            'deleted_files' => 'nullable|array',
            'deleted_files.*' => 'integer|exists:transaction_files,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'transaction_date.required' => 'Ngày giao dịch là bắt buộc.',
            'transaction_date.date' => 'Ngày giao dịch không hợp lệ.',
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.string' => 'Tiêu đề phải là chuỗi ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'type.required' => 'Loại giao dịch là bắt buộc.',
            'type.in' => 'Loại giao dịch phải là thu hoặc chi.',
            'amount.required' => 'Số tiền là bắt buộc.',
            'amount.numeric' => 'Số tiền phải là số.',
            'amount.min' => 'Số tiền phải lớn hơn hoặc bằng 0.',
            'files.array' => 'Tệp đính kèm phải là mảng.',
            'files.*.file' => 'Mỗi tệp phải là file hợp lệ.',
            'files.*.mimes' => 'Chỉ cho phép tệp PDF.',
            'files.*.max' => 'Mỗi tệp không được vượt quá 10MB.',
            'deleted_files.array' => 'Danh sách tệp xóa phải là mảng.',
            'deleted_files.*.integer' => 'ID tệp xóa phải là số nguyên.',
            'deleted_files.*.exists' => 'Tệp cần xóa không tồn tại.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'transaction_date' => 'ngày giao dịch',
            'title' => 'tiêu đề',
            'description' => 'mô tả',
            'type' => 'loại giao dịch',
            'amount' => 'số tiền',
            'files' => 'tệp đính kèm',
            'deleted_files' => 'tệp cần xóa',
        ];
    }
}
