<?php

namespace App\Livewire\Personnel\Children;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\ChildrenRepositoryInterface;
use Livewire\Attributes\Title;

#[Title('Thiếu Nhi')]
class Children extends Component
{
    use WithPagination;

    protected ChildrenRepositoryInterface $childrenRepository;


    public function boot(ChildrenRepositoryInterface $childrenRepository)
    {
        $this->childrenRepository = $childrenRepository;
    }

    public function render()
    {
        $childrens = $this->childrenRepository
            ->paginate(15);

        return view('livewire.personnel.children.childrens', [
            'childrens' => $childrens,
        ]);
    }

    public function addChildren()
    {
        $this->dispatch('addChildren');
    }

    public function editChildren($id){
        $this->dispatch('editChildren', $id);
    }

    public function deleteChildren($id){
        $this->dispatch('deleteChildren', $id);
    }
}
