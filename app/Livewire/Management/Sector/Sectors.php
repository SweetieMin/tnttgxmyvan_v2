<?php

namespace App\Livewire\Management\Sector;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\SectorRepositoryInterface;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;
use Livewire\Attributes\Title;

#[Title('Ngành Sinh Hoạt')]
class Sectors extends Component
{
    use WithPagination;

    protected SectorRepositoryInterface $sectorRepository;
    protected AcademicYearRepositoryInterface $academicYearRepository;

    public $search = '';
    
    public $yearFilter = null;

    public function mount()
    {
        $ongoing = $this->academicYearRepository->getAcademicOngoingNow();
        $this->yearFilter = $ongoing?->id;
    }

    public function boot(SectorRepositoryInterface $sectorRepository, AcademicYearRepositoryInterface $academicYearRepository)
    {
        $this->sectorRepository = $sectorRepository;
        $this->academicYearRepository = $academicYearRepository;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $sectors = $this->sectorRepository->sectorWithSearchAndYear($this->search, $this->yearFilter);

        $years = $this->academicYearRepository->all();

        return view('livewire.management.sector.sectors', [
            'sectors' => $sectors,
            'years' => $years,
            'selectedYear' => $this->yearFilter,
        ]);
    }

    public function addSector()
    {
        $this->dispatch('addSector');
    }

    public function editSector($id){
        $this->dispatch('editSector', $id);
    }

    public function deleteSector($id){
        $this->dispatch('deleteSector', $id);
    }

    public function updateSectorsOrdering($ids)
    {
        $success = $this->sectorRepository->updateOrdering($ids);

        if ($success) {
            session()->flash('success', 'Sắp xếp ngành sinh hoạt thành công!');
        } else {
            session()->flash('error', 'Sắp xếp thất bại! Vui lòng thử lại.');
        }

        $this->redirectRoute('admin.management.sectors', navigate: true);
    }
}
