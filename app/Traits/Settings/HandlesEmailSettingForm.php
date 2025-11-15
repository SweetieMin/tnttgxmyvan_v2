<?php

namespace App\Traits\Settings;

trait HandlesEmailSettingForm
{

    public $portValue = [
        [
            'encryption' => 'tls',
            'port' => 587,
        ],
        [
            'encryption' => 'ssl',
            'port' => 465,
        ],
    ];

    public function updatedEncryption($value)
    {
        if (empty($value)) {
            return;
        }

        $match = collect($this->portValue)->firstWhere('encryption', $value);

        if ($match) {
            $this->port = $match['port'];
        }
    }

    protected function resetForm()
    {
        $this->resetErrorBag();
    }
}
