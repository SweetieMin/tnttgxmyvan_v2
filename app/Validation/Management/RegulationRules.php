<?php

namespace App\Validation\Management;

use Illuminate\Validation\Rule;

class RegulationRules
{
    public static function rules($regulationID = null): array
    {
        return [
            'academic_year_id' => 'required|exists:academic_years,id',
            'type' => 'required|in:plus,minus',
            'points' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ];
    }

    public static function messages(): array
    {
        return [
           'points.required' => 'Vui lòng nhập điểm',
           'description.required' => 'Vui lòng nhập mô tả',
           'description.max' => 'Mô tả không được vượt quá 255 ký tự',
        ];
    }
}
