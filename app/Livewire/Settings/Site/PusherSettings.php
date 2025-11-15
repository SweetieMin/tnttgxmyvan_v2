<?php

namespace App\Livewire\Settings\Site;

use Flux\Flux;
use Livewire\Component;
use App\Models\PusherSetting;
use Livewire\Attributes\Title;
use App\Validation\Setting\PusherRules;

#[Title('Cấu hình Pusher')]

class PusherSettings extends Component
{

    public $app_id = '';
    public $key = '';
    public $secret = '';
    public $cluster = 'ap1';
    public $port = '443';
    public $scheme = 'https';

    public function mount()
    {
        $pusherSetting = PusherSetting::first();
        if (!$pusherSetting) {
            return;
        }

        $this->app_id = $pusherSetting?->app_id;
        $this->key = $pusherSetting?->key;
        $this->secret = $pusherSetting?->secret;
        $this->cluster = $pusherSetting?->cluster;
        $this->port = $pusherSetting?->port;
        $this->scheme = $pusherSetting?->scheme;

    }

    public function rules()
    {
        return PusherRules::rules();
    }

    public function messages()
    {
        return PusherRules::messages();
    }

    public function render()
    {
        return view('livewire.settings.site.pusher-settings');
    }


    public function updatePusherSettings()
    {
        $this->validate();

        $pusherSetting = PusherSetting::first();
        if (!$pusherSetting) {
            $pusherSetting = new PusherSetting();
        }

        $pusherSetting->app_id = $this->app_id;
        $pusherSetting->key = $this->key;
        $pusherSetting->secret = $this->secret;
        $pusherSetting->cluster = $this->cluster;
        $pusherSetting->port = $this->port;
        $pusherSetting->scheme = $this->scheme;
        $pusherSetting->save();

        Flux::toast(
            heading: 'Thành công',
            text: 'Cấu hình Pusher đã được cập nhật thành công.',
            variant: 'success',
        );

        $this->redirectRoute('admin.settings.pusher', navigate: true);

    }

}
