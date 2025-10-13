<?php

namespace App\Http\Requests\Access;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ordering' => 'required|integer|min:1',
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
            'name.required' => 'Tên vai trò là bắt buộc.',
            'name.string' => 'Tên vai trò phải là chuỗi ký tự.',
            'name.max' => 'Tên vai trò không được vượt quá 255 ký tự.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'ordering.required' => 'Thứ tự là bắt buộc.',
            'ordering.integer' => 'Thứ tự phải là số nguyên.',
            'ordering.min' => 'Thứ tự phải lớn hơn hoặc bằng 1.',
        ];
    }
}
