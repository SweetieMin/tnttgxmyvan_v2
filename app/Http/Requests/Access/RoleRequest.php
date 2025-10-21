<?php

namespace App\Http\Requests\Access;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $roleId = $this->route('role'); // Get the role ID from the route parameter
        
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($roleId)
            ],
            'description' => 'nullable|string',
            'ordering' => 'required|integer|min:1',
            'managed_role_ids' => 'array',
            'managed_role_ids.*' => [
                'integer',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($roleId) {
                    // Prevent role from managing itself
                    if ($value == $roleId) {
                        $fail('Vai trò không thể quản lý chính nó.');
                    }
                },
            ],  
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
            'name.unique' => 'Tên vai trò này đã tồn tại trong hệ thống.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'ordering.required' => 'Thứ tự là bắt buộc.',
            'ordering.integer' => 'Thứ tự phải là số nguyên.',
            'ordering.min' => 'Thứ tự phải lớn hơn hoặc bằng 1.',
            'managed_role_ids.array' => 'Danh sách vai trò quản lý phải là mảng.',
            'managed_role_ids.*.integer' => 'ID vai trò quản lý phải là số nguyên.',
            'managed_role_ids.*.exists' => 'Vai trò quản lý được chọn không tồn tại.',
        ];
    }
}
