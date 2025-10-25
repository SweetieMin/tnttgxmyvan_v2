<?php

namespace App\Validation\Management;

use Illuminate\Validation\Rule;

class SectorRules
{
    public static function rules($sectorID = null, $academicYearId = null, $programId = null): array
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
            'sector' => [
                'required',
                'string',
                'max:255',
                // 🔹 Ràng buộc duy nhất theo năm học + chương trình
                Rule::unique('sectors', 'sector')
                    ->where(fn($query) => $query
                        ->where('academic_year_id', $academicYearId)
                        ->where('program_id', $programId))
                    ->ignore($sectorID),
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

            'sector.required' => 'Vui lòng nhập tên ngành sinh hoạt.',
            'sector.unique'   => 'Tên ngành sinh hoạt này đã tồn tại trong cùng niên khoá và chương trình học.',
            'sector.max'      => 'Tên ngành sinh hoạt không được vượt quá 255 ký tự.',
        ];
    }
}
