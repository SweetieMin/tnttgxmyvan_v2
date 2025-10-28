<?php

namespace App\Livewire\Personnel\Catechist;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\CatechistRepositoryInterface;
use Livewire\Attributes\Title;

#[Title('Giáo Lý Viên')]
class Catechists extends Component
{
    use WithPagination;

    protected CatechistRepositoryInterface $catechistRepository;


    public function boot(CatechistRepositoryInterface $catechistRepository)
    {
        $this->catechistRepository = $catechistRepository;
    }

    public function render()
    {
        $catechists = $this->catechistRepository
            ->paginate(15);

        return view('livewire.personnel.catechist.catechists', [
            'catechists' => $catechists,
        ]);
    }

    public function addCatechist()
    {
        $this->dispatch('addCatechist');
    }

    public function editCatechist($id){
        $this->dispatch('editCatechist', $id);
    }

    public function deleteCatechist($id){
        $this->dispatch('deleteCatechist', $id);
    }
}
