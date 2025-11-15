<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class GeneralSettingService
{
    public function checkValidUrl($url, ?string $brand = null)
    {
        // Loại bỏ khoảng trắng và dấu # ở đầu/cuối
        $url = trim($url);
        $url = rtrim($url, '#');
        
        if (empty($url)) {
            return false;
        }

        // Kiểm tra format URL
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Nếu có brand, kiểm tra URL có chứa brand hay không
        if ($brand !== null) {
            $urlLower = strtolower($url);
            $brandLower = strtolower($brand);
            if (!str_contains($urlLower, $brandLower)) {
                return false;
            }
        }

        // Với các mạng xã hội, không cần kiểm tra HTTP request
        // vì họ thường block hoặc redirect HEAD requests
        $socialNetworks = ['facebook', 'instagram', 'youtube', 'tiktok'];
        if ($brand !== null && in_array(strtolower($brand), $socialNetworks)) {
            // Chỉ cần kiểm tra format URL và brand, không cần kiểm tra HTTP
            return true;
        }

        // Với các URL khác, vẫn kiểm tra HTTP request
        try {
            $response = Http::timeout(5)->head($url);
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

}