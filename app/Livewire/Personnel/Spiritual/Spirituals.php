<?php

namespace App\Livewire\Personnel\Spiritual;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use App\Repositories\Interfaces\SpiritualRepositoryInterface;

#[Title('Linh hướng')]
class Spirituals extends Component
{
    use WithPagination;

    protected SpiritualRepositoryInterface $spiritualRepository;

    public $search;

    public $perPage;


    public function boot(SpiritualRepositoryInterface $spiritualRepository)
    {
        $this->spiritualRepository = $spiritualRepository;
    }

    #[Computed]
    public function spirituals()
    {
        $perPage = $this->perPage === '' || $this->perPage === null ? null : (int) $this->perPage;

        return $this->spiritualRepository
            ->getSpiritualWithSearchAndPage($this->search, $perPage);
    }

    public function render()
    {
        return view('livewire.personnel.spiritual.spirituals',[
            'spirituals' => $this->spirituals,
        ]);
    }

    public function addSpiritual()
    {
        $this->redirectRoute('admin.personnel.spirituals.action', ['parameter' => 'addSpiritual'], navigate: true);
    }

    public function editSpiritual($id){
        $this->redirectRoute('admin.personnel.spirituals.action', ['parameter' => 'editSpiritual', 'spiritualID' => $id, 'tab' => 'profile'], navigate: true);
    }

    public function deleteSpiritual($id){
        $this->dispatch('deleteSpiritual', $id);
    }
}
