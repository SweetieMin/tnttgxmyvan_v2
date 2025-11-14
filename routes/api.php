<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Http\Controllers\Api\TelegramController;

Route::post('/telegram/webhook', [TelegramController::class, 'handle']);

Route::get("/get-updates", function () {
    try {
        // Kiểm tra bot token
        $botToken = env('TELEGRAM_BOT_TOKEN');
        if (empty($botToken)) {
            return response()->json([
                'error' => 'TELEGRAM_BOT_TOKEN chưa được cấu hình trong .env',
                'config' => config('telegram.bots.mybot.token'),
            ], 400);
        }

        // Kiểm tra và tắt webhook nếu đang bật (cần thiết cho local development)
        $webhookInfo = Telegram::getWebhookInfo();
        $webhookUrl = is_object($webhookInfo) ? ($webhookInfo->url ?? null) : ($webhookInfo['url'] ?? null);
        
        if (!empty($webhookUrl)) {
            Telegram::removeWebhook();
            return response()->json([
                'success' => false,
                'message' => 'Webhook đang được bật. Đã tự động tắt webhook.',
                'webhook_url' => $webhookUrl,
                'instruction' => 'Vui lòng refresh lại trang này để lấy updates.',
                'note' => 'Khi chạy local, bạn cần tắt webhook để dùng getUpdates().',
            ]);
        }

        // Lấy updates với các tham số tùy chọn
        $updates = Telegram::getUpdates([
            'offset' => 0, // Bắt đầu từ update đầu tiên
            'limit' => 100, // Giới hạn số lượng updates
            'timeout' => 0, // Không chờ updates mới
        ]);

        return response()->json([
            'success' => true,
            'count' => count($updates),
            'updates' => $updates,
            'bot_token_configured' => !empty($botToken),
            'webhook_status' => 'disabled',
            'note' => 'Để nhận updates mới, gửi message đến bot trên Telegram rồi refresh lại trang này.',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});