<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     */
    public function creating(User $user): void
    {
        // 🔹 1. Tạo token ngẫu nhiên 64 ký tự
        $user->token = Str::random(64);

        // 🔹 2. Nếu đã có account_code (nhập tay hoặc import) thì bỏ qua
        if (!empty($user->account_code)) {
            return;
        }

        // 🔹 3. Sinh account_code = MV + ddmmyy + 2 số ngẫu nhiên
        $datePart = !empty($user->birthday)
            ? date('dmy', strtotime($user->birthday))  // ví dụ: 310725
            : now()->format('dmy');

        // 🔹 4. Sinh 2 số ngẫu nhiên (00-99)
        $randPart = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);

        // 🔹 5. Ghép thành mã hoàn chỉnh
        $user->account_code = 'MV' . $datePart . $randPart;

        // 🔹 6. (Tuỳ chọn) đảm bảo không trùng
        while (User::where('account_code', $user->account_code)->exists()) {
            $randPart = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
            $user->account_code = 'MV' . $datePart . $randPart;
        }
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
