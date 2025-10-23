<?php

namespace App\Repositories\Traits;

use Illuminate\Support\Facades\Log;
use Exception;

/**
 * 🛡️ Trait: HandlesSafeExecution
 * 
 * Trait này cung cấp phương thức `safeExecute()` giúp bao bọc mọi thao tác
 * truy vấn hoặc cập nhật CSDL trong khối try/catch để tránh crash hệ thống.
 * 
 * ✅ Tính năng chính:
 * - Tự động bắt lỗi Exception
 * - Ghi log chi tiết lỗi (repository, model, message)
 * - Trả về thông báo lỗi thân thiện
 *
 * 👉 Mục tiêu: Giúp toàn bộ repository an toàn và thống nhất khi thao tác dữ liệu.
 */
trait HandlesSafeExecution
{
    /**
     * Thực thi callback trong môi trường an toàn.
     *
     * @param  callable  $callback  Hàm callback chứa logic cần chạy.
     * @param  string    $errorMessage  Thông báo lỗi thân thiện hiển thị khi có lỗi.
     * @return mixed
     * @throws Exception
     */
    protected function safeExecute(callable $callback, string $errorMessage)
    {
        try {
            return $callback();
        } catch (Exception $e) {
            Log::error($errorMessage . ' | ' . $e->getMessage(), [
                'repository' => static::class,
                'model' => $this->model::class ?? null,
            ]);

            throw new Exception($errorMessage);
        }
    }
}
