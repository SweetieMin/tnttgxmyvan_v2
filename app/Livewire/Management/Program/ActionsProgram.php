<?php

namespace App\Livewire\Management\Program;

use Livewire\Component;
use App\Repositories\Interfaces\ProgramRepositoryInterface;

class ActionsProgram extends Component
{

    public $isEditProgramMode = false;

    protected ProgramRepositoryInterface $programRepository;

    public function boot(ProgramRepositoryInterface $programRepository)
    {
        $this->programRepository = $programRepository;
    }

    public function render()
    {
        return view('livewire.management.program.actions-program');
    }
}
