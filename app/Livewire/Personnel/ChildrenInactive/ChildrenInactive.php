<?php

namespace App\Livewire\Personnel\ChildrenInactive;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\ChildrenInactiveRepositoryInterface;
use Livewire\Attributes\Title;

#[Title('Thiếu Nhi đã tốt nghiệp')]
class ChildrenInactive extends Component
{
    use WithPagination;

    protected ChildrenInactiveRepositoryInterface $children_inactiveRepository;


    public function boot(ChildrenInactiveRepositoryInterface $children_inactiveRepository)
    {
        $this->children_inactiveRepository = $children_inactiveRepository;
    }

    public function render()
    {
        $children_inactives = $this->children_inactiveRepository
            ->paginate(15);

        return view('livewire.personnel.children-inactive.children-inactives', [
            'children_inactives' => $children_inactives,
        ]);
    }

    public function addChildrenInactive()
    {
        $this->dispatch('addChildrenInactive');
    }

    public function editChildrenInactive($id){
        $this->dispatch('editChildrenInactive', $id);
    }

    public function deleteChildrenInactive($id){
        $this->dispatch('deleteChildrenInactive', $id);
    }
}
