<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class TelegramController extends Controller
{
    public function handle()
    {
        $update = json_decode(file_get_contents('php://input'), true);

        if (!isset($update['message'])) {
            return;
        }

        $chatId = $update['message']['chat']['id'];
        $text = $update['message']['text'] ?? '';

        // Kiểm tra nếu message chứa "Số điện thoại mới:" (từ bot/service gửi)
        if (preg_match('/Số điện thoại mới:\s*((0|\+84)(3|5|7|8|9)[0-9]{8})/', $text, $matches)) {
            $phoneNumber = trim($matches[1]);
            
            // Tạo OTP ngẫu nhiên 6 số
            $otp = rand(100000, 999999);

            // Lưu vào Cache 5 phút với key là số điện thoại
            Cache::put("otp_phone_{$phoneNumber}", [
                "phone" => $phoneNumber,
                "otp" => $otp,
                "created_at" => now(),
            ], 300);

            $this->sendMessage($chatId, "Mã OTP cho số điện thoại *{$phoneNumber}* là: *{$otp}*\n\nMã OTP có hiệu lực trong 5 phút.");

            return;
        }

        // Người dùng gửi số điện thoại trực tiếp
        if (preg_match('/^(0|\+84)(3|5|7|8|9)[0-9]{8}$/', $text)) {

            // Tạo OTP ngẫu nhiên 6 số
            $otp = rand(100000, 999999);

            // Lưu vào Cache 5 phút
            Cache::put("otp_{$chatId}", [
                "phone" => $text,
                "otp" => $otp,
            ], 300);

            $this->sendMessage($chatId, "Mã OTP của bạn là: *{$otp}*");

            return;
        }

        $this->sendMessage($chatId, "Vui lòng nhập số điện thoại hợp lệ.");
    }

    private function sendMessage($chatId, $text)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chatId}&text=" . urlencode($text) . "&parse_mode=Markdown");
    }
}