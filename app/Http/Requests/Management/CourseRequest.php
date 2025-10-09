<?php

namespace App\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có quyền gửi request này không
     */
    public function authorize(): bool
    {
        return true; // hoặc thêm kiểm tra quyền ở đây
    }

    /**
     * Quy tắc validate dữ liệu
     */
    public function rules(): array
    {
        $courseId = $this->route('course');
        
        return [
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'name' => [
                'required', 
                'string', 
                'min:10',
                'max:255', 
                'unique:courses,name,' . $courseId
            ],
            'description' => ['nullable', 'string','min:10','max:255'],
        ];
    }

    /**
     * Thông báo lỗi tuỳ chỉnh
     */
    public function messages(): array
    {
        return [
            'academic_year_id.required' => 'Niên khóa là bắt buộc.',
            'academic_year_id.integer' => 'Niên khóa phải là số nguyên.',
            'academic_year_id.exists' => 'Niên khóa không tồn tại.',
            'name.required' => 'Tên lớp giáo lý là bắt buộc.',
            'name.string' => 'Tên lớp giáo lý phải là chuỗi.',
            'name.min' => 'Tên lớp giáo lý phải có ít nhất 10 ký tự.',
            'name.max' => 'Tên lớp giáo lý phải có ít hơn 255 ký tự.',
            'name.unique' => 'Tên lớp giáo lý đã tồn tại.',
            'description.string' => 'Mô tả phải là chuỗi.',
            'description.min' => 'Mô tả phải có ít nhất 10 ký tự.',
            'description.max' => 'Mô tả phải có ít hơn 255 ký tự.',
        ];
    }
}
