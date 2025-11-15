<?php

namespace App\Validation\Setting;

class PusherRules
{
    public static function rules(): array
    {
        return [
            'app_id' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255'],
            'secret' => ['required', 'string', 'max:255'],
            
            // Pusher cluster: ap1, us2, eu...
            'cluster' => ['required', 'string', 'in:ap1,us2,eu'],
            
            // Port nên là số, 1 → 65535
            'port' => ['required', 'integer', 'min:1', 'max:65535'],

            // scheme: http hoặc https
            'scheme' => ['required', 'string', 'in:http,https'],
        ];
    }

    public static function messages(): array
    {
        return [
            'app_id.required' => 'App ID là bắt buộc.',
            'app_id.string' => 'App ID phải là chuỗi ký tự.',
            
            'key.required' => 'Key là bắt buộc.',
            'key.string' => 'Key phải là chuỗi ký tự.',
            
            'secret.required' => 'Secret là bắt buộc.',
            'secret.string' => 'Secret phải là chuỗi ký tự.',
            
            'cluster.required' => 'Vui lòng chọn cluster.',
            'cluster.in' => 'Cluster không hợp lệ. Chỉ chấp nhận ap1, us2, eu.',

            'port.required' => 'Cổng (Port) là bắt buộc.',
            'port.integer' => 'Port phải là số.',
            'port.min' => 'Port phải lớn hơn 0.',
            'port.max' => 'Port không hợp lệ.',
            
            'scheme.required' => 'Scheme là bắt buộc.',
            'scheme.in' => 'Scheme phải là http hoặc https.',
        ];
    }
}
