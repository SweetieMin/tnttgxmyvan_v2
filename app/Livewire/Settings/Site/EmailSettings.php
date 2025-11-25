<?php

namespace App\Livewire\Settings\Site;

use Flux\Flux;
use Livewire\Component;
use App\Models\MailSetting;
use Livewire\Attributes\Title;
use App\Validation\Setting\EmailRules;
use App\Traits\Settings\HandlesEmailSettingForm;

#[Title('Cấu hình Email')]

class EmailSettings extends Component
{

    use HandlesEmailSettingForm;

    public $mailer = "smtp";
    public $host = "smtp.gmail.com";
    public $port = "587";
    public $username;
    public $password;
    public $encryption = "tls";
    public $from_address;
    public $from_name;

    public $isHavePassword = false;
    public $showPasswordInput = false;

    public function togglePasswordInput()
    {
        $this->showPasswordInput = !$this->showPasswordInput;

        // reset password mỗi lần mở input
        if ($this->showPasswordInput) {
            $this->password = null;
        }
    }


    public function mount()
    {

        $emailSetting = MailSetting::first();

        if (!$emailSetting) {
            return;
        }

        $this->mailer = $emailSetting?->mailer;
        $this->host = $emailSetting?->host;
        $this->port = $emailSetting?->port;
        $this->username = $emailSetting?->username;
        $this->password = null;
        $this->isHavePassword = !empty($emailSetting->password);
        $this->showPasswordInput = !$this->isHavePassword;
        $this->encryption = $emailSetting?->encryption;
        $this->from_address = $emailSetting?->from_address;
        $this->from_name = $emailSetting?->from_name;
    }

    public function rules()
    {
        return EmailRules::rules($this->isHavePassword);
    }

    public function messages()
    {
        return EmailRules::messages();
    }


    public function render()
    {
        return view('livewire.settings.site.email-settings');
    }

    public function updateEmailSettings()
    {
        $this->validate();

        $emailSettings = MailSetting::first();

        if (!$emailSettings) {
            $emailSettings = new MailSetting();
        }

        $emailSettings->mailer = $this->mailer;
        $emailSettings->host = $this->host;
        $emailSettings->port = (int) $this->port;
        $emailSettings->username = $this->username;
        if (!empty($this->password)) {
            $emailSettings->password = encrypt($this->password);
        }
        $emailSettings->encryption = $this->encryption;
        $emailSettings->from_address = $this->from_address;
        $emailSettings->from_name = $this->from_name;
        $emailSettings->save();

        Flux::toast(
            heading: 'Thành công',
            text: 'Cấu hình email cập nhật thành công.',
            variant: 'success',
        );

        $this->redirectRoute('admin.settings.email', navigate: true);
    }
}
