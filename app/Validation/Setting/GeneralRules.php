<?php

namespace App\Validation\Setting;


class GeneralRules
{
    public static function rules(): array
    {
        return [
            'site_title' => 'required|string|max:255',
            'site_email' => 'required|email|max:255',
            'site_phone' => 'required|string|max:255',
            'site_meta_keywords' => 'required|string|max:255',
            'site_meta_description' => 'required|string|max:65000',
            'facebook_url' => 'nullable|url|string|max:255',
            'instagram_url' => 'nullable|url|string|max:255',
            'youtube_url' => 'nullable|url|string|max:255',
            'tikTok_url' => 'nullable|url|string|max:255',
        ];
    }

    public static function messages(): array
    {
        return [
            'site_title.required' => 'Tiêu đề trang web là bắt buộc.',
            'site_email.required' => 'Email là bắt buộc.',
            'site_email.email' => 'Email không hợp lệ.',
            'site_phone.required' => 'Số điện thoại là bắt buộc.',
            'site_meta_keywords.required' => 'Từ khóa meta là bắt buộc.',
            'site_meta_description.required' => 'Mô tả meta là bắt buộc.',
            'facebook_url.url' => 'URL Facebook không hợp lệ.',
            'instagram_url.url' => 'URL Instagram không hợp lệ.',
            'youtube_url.url' => 'URL YouTube không hợp lệ.',
            'tikTok_url.url' => 'URL TikTok không hợp lệ.',
        ];
    }
}
