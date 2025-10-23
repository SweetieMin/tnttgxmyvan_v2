<?php

namespace App\Validation\Management;

use Illuminate\Validation\Rule;

class ProgramRules
{
    public static function rules($programID = null): array
    {
        return [
            'course' => [
                'required',
                'string',
                'max:255',
                Rule::unique('programs', 'course')->ignore($programID),
            ],
            'sector' => [
                'required',
                'string',
                'max:255',
                Rule::unique('programs', 'sector')->ignore($programID),
            ],
            'description' => 'nullable|string|max:255',
        ];
    }

    public static function messages(): array
    {
        return [
            'course.required' => 'Nhập tên Giáo lý.',
            'course.string' => 'Tên Giáo lý phải là chuỗi ký tự hợp lệ.',
            'course.max' => 'Tên Giáo lý không được vượt quá 255 ký tự.',
            'course.unique' => 'Tên Giáo lý này đã tồn tại trong hệ thống.',

            'sector.required' => 'Nhập tên Ngành sinh hoạt.',
            'sector.string' => 'Tên Ngành sinh hoạt phải là chuỗi ký tự hợp lệ.',
            'sector.max' => 'Tên Ngành sinh hoạt không được vượt quá 255 ký tự.',
            'sector.unique' => 'Tên Ngành sinh hoạt này đã tồn tại trong hệ thống.',

            'description.string' => 'Mô tả phải là chuỗi ký tự hợp lệ.',
            'description.max' => 'Mô tả không được vượt quá 255 ký tự.',
        ];
    }
}
