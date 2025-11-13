<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Session;

use Livewire\Component;
use App\Models\UserSetting;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Title('Giao diện')]

class Appearance extends Component
{

    public $notification_sound = true;

    public $notification_volume = 100;

    public function mount()
    {

        $userSetting = UserSetting::where('user_id', Auth::id())->first();

        $this->notification_sound = $userSetting?->notification_sound ?? true;
        $this->notification_volume = $userSetting?->notification_volume ?? 100;
    }

    public function updatedNotificationVolume($value)
    {
        $this->notification_volume = $value;
        $this->dispatch('updateVolume', [
            'value' => $value
        ]);
    }

    public function saveSettingAppearance()
    {
        try {
            UserSetting::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'notification_sound' => $this->notification_sound,
                    'notification_volume' => $this->notification_volume,
                ]
            );

            $this->dispatch('appearance-updated','Đã lưu');
        } catch (\Exception $e) {
            $this->dispatch('appearance-updated','Đã bị lỗi');
        }

    }
}
