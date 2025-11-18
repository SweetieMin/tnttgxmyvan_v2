<?php

namespace App\Support\User;

use App\Models\User;
use Illuminate\Support\Str;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class UserHelper
{
    public static function separateFullName(?string $full_name): array
    {
        // Chuẩn hóa khoảng trắng
        $value = trim(preg_replace('/\s+/', ' ', (string) $full_name));

        // Nếu chuỗi rỗng → trả về name & last_name rỗng
        if ($value === '') {
            return [
                'last_name' => '',
                'name'      => '',
            ];
        }

        // Tách tên
        $parts = explode(' ', $value);

        $name = array_pop($parts);     // Tên (từ cuối)
        $last_name = implode(' ', $parts); // Họ + đệm

        return [
            'last_name' => $last_name,
            'name'      => $name,
        ];
    }

    public static function generateTokenQrCode()
    {
        $token = Str::random(64);
        $svg = (new Writer(
            new ImageRenderer(
                new RendererStyle(250, 1, null, null, Fill::uniformColor(new Rgb(255, 255, 255), new Rgb(0, 0, 0))),
                new SvgImageBackEnd
            )
        ))->writeString(url('/profile/' . $token));

        return [
            'svg' => $svg,
            'token' => $token,
        ];
    }
}
