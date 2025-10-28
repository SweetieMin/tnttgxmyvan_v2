<?php

namespace App\Livewire\Personnel\Scouter;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\ScouterRepositoryInterface;
use Livewire\Attributes\Title;

#[Title('Huynh-Dự-Đội Trưởng')]
class Scouters extends Component
{
    use WithPagination;

    protected ScouterRepositoryInterface $scouterRepository;


    public function boot(ScouterRepositoryInterface $scouterRepository)
    {
        $this->scouterRepository = $scouterRepository;
    }

    public function render()
    {
        $scouters = $this->scouterRepository
            ->paginate(15);

        return view('livewire.personnel.scouter.scouters', [
            'scouters' => $scouters,
        ]);
    }

    public function addScouter()
    {
        $this->dispatch('addScouter');
    }

    public function editScouter($id){
        $this->dispatch('editScouter', $id);
    }

    public function deleteScouter($id){
        $this->dispatch('deleteScouter', $id);
    }
}
