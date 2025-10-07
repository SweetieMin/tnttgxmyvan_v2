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
        // 🔹 1. Sinh token ngẫu nhiên 64 ký tự
        $user->token = Str::random(64);

        // 🔹 2. Sinh account_code = MV + ddmmyy + 2 số ngẫu nhiên
        if (!empty($user->birthday)) {
            $datePart = date('dm y', strtotime($user->birthday)); // vd: 310725
            $randPart = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT); // vd: 07
            $user->account_code = 'MV' . str_replace(' ', '', $datePart) . $randPart; // => MV31072507
        } else {
            // Nếu chưa có ngày sinh (phòng trường hợp thêm admin, giáo viên,...)
            $user->account_code = 'MV' . now()->format('dm y') . str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
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
