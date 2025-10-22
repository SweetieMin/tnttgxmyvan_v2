<?php

namespace App\Livewire\Management\Program;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Repositories\Interfaces\ProgramRepositoryInterface;

#[Title('Chương trình học')]
class Programs extends Component
{

    use WithPagination;

    protected ProgramRepositoryInterface $programRepository;

    public function boot(ProgramRepositoryInterface $programRepository)
    {
        $this->programRepository = $programRepository;
    }

    public function render()
    {

        $programs = $this->programRepository->paginate(15);

        return view('livewire.management.program.programs',[
            'programs' => $programs
        ]);
    }

    public function addProgram()
    {
        $this->dispatch('addProgram');
    }

    public function editProgram($id){
        $this->dispatch('editProgram', $id);
    }

    public function deleteProgram($id){
        $this->dispatch('deleteProgram', $id);
    }
}
