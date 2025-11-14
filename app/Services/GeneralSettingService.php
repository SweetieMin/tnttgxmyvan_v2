<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class GeneralSettingService
{
    public function checkValidUrl($url, ?string $brand = null)
    {
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

        try {
            $response = Http::timeout(5)->head($url);
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

}