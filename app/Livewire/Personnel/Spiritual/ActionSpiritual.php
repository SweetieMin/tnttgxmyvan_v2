<?php

namespace App\Livewire\Personnel\Spiritual;

use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;

class ActionSpiritual extends Component
{

    public $isEditSpiritualMode = false;

    public function render()
    {
        return view('livewire.personnel.spiritual.action-spiritual');
    }

    #[On('addSpiritual')]
    public function addSpiritual()
    {
        Flux::modal('action-spiritual')->show();
    }
}
