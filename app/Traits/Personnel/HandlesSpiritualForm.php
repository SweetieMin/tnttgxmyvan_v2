<?php

namespace App\Traits\Personnel;

use App\Models\User;
use App\Support\User\UserHelper;
use Illuminate\Support\Facades\Hash;


trait HandlesSpiritualForm
{
    protected function resetForm()
    {
        $this->reset([
            'christian_name',
            'full_name',
            'name',
            'last_name',
            'account_code',
            'gender',
            'position',
            'birthday',
            'status_login',
            'phone',
            'address',
            'email',
            'bio',
            'password',
        ]);

        $this->isEditSpiritualMode = false;

        $this->resetErrorBag();
    }

    public function updatedBirthday($value){
        $datePart = !empty($value)
            ? date('dmy', strtotime($value))  // ví dụ: 310725
            : now()->format('dmy');

        // 🔹 4. Sinh 2 số ngẫu nhiên (00-99)
        $randPart = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);

        // 🔹 5. Ghép thành mã hoàn chỉnh
        $this->account_code = 'MV' . $datePart . $randPart;

        // 🔹 6. (Tuỳ chọn) đảm bảo không trùng
        while (User::where('account_code', $this->account_code)->exists()) {
            $randPart = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
            $this->account_code = 'MV' . $datePart . $randPart;
        }
        $this->password = Hash::make($this->account_code);
    }

    public function updatedFullName($value)
    {
        $parts = UserHelper::separateFullName($value);
        $this->name = $parts['name'];
        $this->last_name = $parts['last_name'];

    }
}
