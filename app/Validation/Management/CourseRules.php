<?php

namespace App\Validation\Management;

use Illuminate\Validation\Rule;

class CourseRules
{
    public static function rules($courseID = null, $academicYearId = null, $programId = null): array
    {
        return [
            'academic_year_id' => [
                'required',
                'integer',
                'exists:academic_years,id',
            ],
            'program_id' => [
                'required',
                'integer',
                'exists:programs,id',
            ],
            'course' => [
                'required',
                'string',
                'max:255',
                // 🔹 Ràng buộc duy nhất theo năm học + chương trình
                Rule::unique('courses', 'course')
                    ->where(fn($query) => $query
                        ->where('academic_year_id', $academicYearId)
                        ->where('program_id', $programId))
                    ->ignore($courseID),
            ],
        ];
    }

    public static function messages(): array
    {
        return [
            'academic_year_id.required' => 'Vui lòng chọn niên khoá.',
            'academic_year_id.exists'   => 'Niên khoá không hợp lệ.',

            'program_id.required' => 'Vui lòng chọn chương trình học.',
            'program_id.exists'   => 'Chương trình học không hợp lệ.',

            'course.required' => 'Vui lòng nhập tên lớp.',
            'course.unique'   => 'Tên lớp này đã tồn tại trong cùng niên khoá và chương trình học.',
            'course.max'      => 'Tên lớp không được vượt quá 255 ký tự.',
        ];
    }
}
