<?php

namespace App\Livewire\Personnel\Spiritual;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\SpiritualRepositoryInterface;
use Livewire\Attributes\Title;

#[Title('Linh hướng')]
class Spirituals extends Component
{
    use WithPagination;

    protected SpiritualRepositoryInterface $spiritualRepository;


    public function boot(SpiritualRepositoryInterface $spiritualRepository)
    {
        $this->spiritualRepository = $spiritualRepository;
    }

    public function render()
    {
        $spirituals = $this->spiritualRepository
            ->paginate(15);

        return view('livewire.personnel.spiritual.spirituals', [
            'spirituals' => $spirituals,
        ]);
    }

    public function addSpiritual()
    {
        $this->redirectRoute('admin.personnel.spiritual.action', ['parameter' => 'addUser'], navigate: true);
    }

    public function editSpiritual($id){
        
    }

    public function deleteSpiritual($id){
        $this->dispatch('deleteSpiritual', $id);
    }
}
