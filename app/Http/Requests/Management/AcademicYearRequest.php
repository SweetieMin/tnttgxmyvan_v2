<?php

namespace App\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;

class AcademicYearRequest extends FormRequest
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
        $academicYearId = $this->route('academic_year');
        
        return [
            'name' => [
                'required', 
                'string', 
                'max:255', 
                'unique:academic_years,name,' . $academicYearId
            ],
            'catechism_start_date' => ['required', 'date'],
            'catechism_end_date' => ['required', 'date', 'after_or_equal:catechism_start_date'],
            'activity_start_date' => ['required', 'date'],
            'activity_end_date' => ['required', 'date', 'after_or_equal:activity_start_date'],
            'status_academic' => ['nullable', 'in:upcoming,ongoing,finished'],
            'catechism_avg_score' => ['required', 'numeric', 'min:1', 'max:10'],
            'catechism_training_score' => ['required', 'numeric', 'min:1', 'max:10'],
            'activity_score' => ['required', 'integer', 'min:1', 'max:1000'],
        ];
    }

    /**
     * Thông báo lỗi tuỳ chỉnh
     */
    public function messages(): array
    {
        return [
            // Tên niên khóa
            'name.required' => 'Tên niên khóa là bắt buộc.',
            'name.string' => 'Tên niên khóa phải là chuỗi ký tự.',
            'name.max' => 'Tên niên khóa không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên niên khóa này đã tồn tại.',
            
            // Ngày bắt đầu Giáo lý
            'catechism_start_date.required' => 'Ngày bắt đầu Giáo lý là bắt buộc.',
            'catechism_start_date.date' => 'Ngày bắt đầu Giáo lý phải là ngày hợp lệ.',
            
            // Ngày kết thúc Giáo lý
            'catechism_end_date.required' => 'Ngày kết thúc Giáo lý là bắt buộc.',
            'catechism_end_date.date' => 'Ngày kết thúc Giáo lý phải là ngày hợp lệ.',
            'catechism_end_date.after_or_equal' => 'Ngày kết thúc Giáo lý phải sau hoặc bằng ngày bắt đầu.',
            
            // Ngày bắt đầu Sinh hoạt
            'activity_start_date.required' => 'Ngày bắt đầu Sinh hoạt là bắt buộc.',
            'activity_start_date.date' => 'Ngày bắt đầu Sinh hoạt phải là ngày hợp lệ.',
            
            // Ngày kết thúc Sinh hoạt
            'activity_end_date.required' => 'Ngày kết thúc Sinh hoạt là bắt buộc.',
            'activity_end_date.date' => 'Ngày kết thúc Sinh hoạt phải là ngày hợp lệ.',
            'activity_end_date.after_or_equal' => 'Ngày kết thúc Sinh hoạt phải sau hoặc bằng ngày bắt đầu.',
            
            // Trạng thái niên khóa
            'status_academic.in' => 'Trạng thái niên khóa phải là: sắp diễn ra, đang diễn ra, hoặc đã kết thúc.',
            
            // Điểm trung bình Giáo lý
            'catechism_avg_score.required' => 'Điểm trung bình Giáo lý là bắt buộc.',
            'catechism_avg_score.numeric' => 'Điểm trung bình Giáo lý phải là số.',
            'catechism_avg_score.min' => 'Điểm trung bình Giáo lý không được nhỏ hơn 1.',
            'catechism_avg_score.max' => 'Điểm trung bình Giáo lý không được lớn hơn 10.',
            
            // Điểm đào tạo Giáo lý
            'catechism_training_score.required' => 'Điểm đào tạo Giáo lý là bắt buộc.',
            'catechism_training_score.numeric' => 'Điểm đào tạo Giáo lý phải là số.',
            'catechism_training_score.min' => 'Điểm đào tạo Giáo lý không được nhỏ hơn 1.',
            'catechism_training_score.max' => 'Điểm đào tạo Giáo lý không được lớn hơn 10.',
            
            // Điểm Sinh hoạt
            'activity_score.required' => 'Điểm Sinh hoạt là bắt buộc.',
            'activity_score.integer' => 'Điểm Sinh hoạt phải là số nguyên.',
            'activity_score.min' => 'Điểm Sinh hoạt không được nhỏ hơn 1.',
            'activity_score.max' => 'Điểm Sinh hoạt không được lớn hơn 1000.',
        ];
    }
}