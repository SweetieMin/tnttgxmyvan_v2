<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $botToken;
    protected ?string $chatId;

    public function __construct()
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN', '');
        $this->chatId = env('TELEGRAM_CHAT_ID', null);
    }

    /**
     * Gửi số điện thoại đến Telegram bot để nhận OTP
     *
     * @param string $phoneNumber
     * @return bool
     */
    public function sendPhoneForOtp(string $phoneNumber): bool
    {
        if (empty($this->botToken) || empty($this->chatId)) {
            Log::warning('Telegram bot token hoặc chat ID chưa được cấu hình');
            return false;
        }

        try {
            $message = "Số điện thoại mới: {$phoneNumber}";
            
            $response = Http::timeout(10)->post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);

            if ($response->successful()) {
                Log::info('Đã gửi số điện thoại đến Telegram bot', [
                    'phone' => $phoneNumber,
                    'chat_id' => $this->chatId,
                ]);
                return true;
            }

            Log::error('Không thể gửi message đến Telegram bot', [
                'response' => $response->body(),
                'status' => $response->status(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Lỗi khi gửi message đến Telegram bot', [
                'error' => $e->getMessage(),
                'phone' => $phoneNumber,
            ]);
            return false;
        }
    }
}

