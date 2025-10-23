<?php

namespace App\Livewire\Management\Program;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\ProgramRepositoryInterface;
use Livewire\Attributes\Title;

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
        $programs = $this->programRepository
            ->paginate(15);

        return view('livewire.management.program.programs', [
            'programs' => $programs,
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

    public function updateProgramsOrdering($ids)
    {
        $success = $this->programRepository->updateOrdering($ids);

        if ($success) {
            session()->flash('success', 'Sắp xếp chương trình học thành công!');
        } else {
            session()->flash('error', 'Sắp xếp thất bại! Vui lòng thử lại.');
        }

        $this->redirectRoute('admin.management.programs', navigate: true);
    }

}
