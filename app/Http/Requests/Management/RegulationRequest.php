<?php

namespace App\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;

class RegulationRequest extends FormRequest
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
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'description' => 'required|string|max:1000',
            'type' => 'required|in:plus,minus',
            'points' => 'required|integer|min:1|max:100',
            'ordering' => 'required|integer|min:1',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'integer|exists:roles,id',
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
            'academic_year_id.required' => 'Niên khóa là bắt buộc.',
            'academic_year_id.integer' => 'Niên khóa phải là số nguyên.',
            'academic_year_id.exists' => 'Niên khóa không tồn tại.',
            'description.required' => 'Nội dung nội quy là bắt buộc.',
            'description.string' => 'Nội dung nội quy phải là chuỗi ký tự.',
            'description.max' => 'Nội dung nội quy không được vượt quá 1000 ký tự.',
            'type.required' => 'Loại nội quy là bắt buộc.',
            'type.in' => 'Loại nội quy phải là "Cộng điểm" hoặc "Trừ điểm".',
            'points.required' => 'Điểm số là bắt buộc.',
            'points.integer' => 'Điểm số phải là số nguyên.',
            'points.min' => 'Điểm số phải lớn hơn hoặc bằng 1.',
            'points.max' => 'Điểm số không được vượt quá 100.',
            'ordering.required' => 'Thứ tự là bắt buộc.',
            'ordering.integer' => 'Thứ tự phải là số nguyên.',
            'ordering.min' => 'Thứ tự phải lớn hơn hoặc bằng 1.',
            'role_ids.array' => 'Vai trò áp dụng phải là danh sách.',
            'role_ids.*.integer' => 'Mỗi vai trò phải là số nguyên.',
            'role_ids.*.exists' => 'Một hoặc nhiều vai trò không tồn tại.',
        ];
    }
}
