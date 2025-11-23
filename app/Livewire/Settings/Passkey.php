<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Quản lý Passkey')]

class Passkey extends Component
{
    public function render()
    {
        return view('livewire.settings.passkey');
    }
}
