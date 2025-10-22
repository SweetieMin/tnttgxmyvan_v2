<?php

namespace App\Validation\Management;

use Illuminate\Validation\Rule;

class AcademicYearRules
{
    public static function rules($academicYearID = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('academic_years', 'name')->ignore($academicYearID),
            ],
            'catechism_start_date' => 'required|date',
            'catechism_end_date' => 'required|date|after_or_equal:catechism_start_date',
            'catechism_avg_score' => 'required|numeric|min:0|max:10',
            'catechism_training_score' => 'required|numeric|min:0|max:10',
            'activity_start_date' => 'required|date',
            'activity_end_date' => 'required|date|after_or_equal:activity_start_date',
            'activity_score' => 'required|numeric|min:0|max:1000',
        ];
    }

    public static function messages(): array
    {
        return [
            // 🔹 Tên niên khóa
            'name.required' => 'Nhập tên niên khóa.',
            'name.string' => 'Tên niên khóa không hợp lệ.',
            'name.max' => 'Tên niên khóa không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên niên khóa này đã tồn tại. Vui lòng chọn năm khác.',

            // 🔹 Giáo lý
            'catechism_start_date.required' => 'Nhập ngày bắt đầu giáo lý.',
            'catechism_start_date.date' => 'Ngày bắt đầu giáo lý không hợp lệ.',
            'catechism_end_date.required' => 'Nhập ngày kết thúc giáo lý.',
            'catechism_end_date.date' => 'Ngày kết thúc giáo lý không hợp lệ.',
            'catechism_end_date.after_or_equal' => 'Ngày kết thúc giáo lý phải sau hoặc bằng ngày bắt đầu.',

            'catechism_avg_score.numeric' => 'Điểm giáo lý phải là số.',
            'catechism_avg_score.min' => 'Điểm giáo lý phải lớn hơn hoặc bằng 0.',
            'catechism_avg_score.max' => 'Điểm giáo lý tối đa là 10.',
            'catechism_avg_score.required' => 'Nhập điểm giáo lý',

            'catechism_training_score.numeric' => 'Điểm chuyên cần phải là số.',
            'catechism_training_score.min' => 'Điểm chuyên cần phải lớn hơn hoặc bằng 0.',
            'catechism_training_score.max' => 'Điểm chuyên cần tối đa là 10.',
            'catechism_training_score.required' => 'Nhập điểm chuyên cần.',

            // 🔹 Sinh hoạt
            'activity_start_date.required' => 'Nhập ngày bắt đầu sinh hoạt.',
            'activity_start_date.date' => 'Ngày bắt đầu sinh hoạt không hợp lệ.',
            'activity_end_date.required' => 'Nhập ngày kết thúc sinh hoạt.',
            'activity_end_date.date' => 'Ngày kết thúc sinh hoạt không hợp lệ.',
            'activity_end_date.after_or_equal' => 'Ngày kết thúc sinh hoạt phải sau hoặc bằng ngày bắt đầu.',

            'activity_score.numeric' => 'Điểm sinh hoạt phải là số.',
            'activity_score.min' => 'Điểm sinh hoạt phải lớn hơn hoặc bằng 0.',
            'activity_score.max' => 'Điểm sinh hoạt tối đa là 1000.',
            'activity_score.required' => 'Nhập điểm sinh hoạt.',

            // 🔹 Trạng thái
            'status_academic.required' => 'Vui lòng chọn trạng thái niên khóa.',
            'status_academic.in' => 'Giá trị trạng thái không hợp lệ.',
        ];
    }
}
