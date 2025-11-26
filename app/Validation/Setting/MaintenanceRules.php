<?php

namespace App\Validation\Setting;


class MaintenanceRules
{
    public static function rules($isMaintenance = false): array
    {
        $rules = [
            'is_maintenance' => 'required|boolean',
        ];

        // Chỉ validate các field này khi bật bảo trì
        if ($isMaintenance) {
            $rules['message'] = 'required|string|max:255';
            $rules['start_at'] = 'required|date_format:H:i';
            $rules['end_at'] = 'required|date_format:H:i|after:start_at';
        }

        return $rules;
    }

    public static function messages(): array
    {
        return [
            'message.required' => 'Thông điệp bảo trì là bắt buộc.',
            'message.string' => 'Thông điệp bảo trì phải là chuỗi.',
            'message.max' => 'Thông điệp bảo trì không được vượt quá 255 ký tự.',
            'start_at.required' => 'Thời gian bắt đầu là bắt buộc.',
            'start_at.date_format' => 'Thời gian bắt đầu không hợp lệ.',
            'end_at.required' => 'Thời gian kết thúc là bắt buộc.',
            'end_at.date_format' => 'Thời gian kết thúc không hợp lệ.',
            'end_at.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
            'is_maintenance.required' => 'Trạng thái bảo trì là bắt buộc.',
            'is_maintenance.boolean' => 'Trạng thái bảo trì phải là true hoặc false.',
        ];
    }
}
