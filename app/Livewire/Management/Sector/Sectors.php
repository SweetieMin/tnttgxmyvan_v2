<?php

namespace App\Livewire\Management\Sector;

use Flux\Flux;
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

    protected $queryString = [
        'search' => ['except' => ''],
        'yearFilter' => ['except' => null],
    ];

    public function mount()
    {
        if (! $this->yearFilter) {
            $ongoing = $this->academicYearRepository->getAcademicOngoingNow();
            $this->yearFilter = $ongoing?->id;
        }
    }

    public function boot(SectorRepositoryInterface $sectorRepository, AcademicYearRepositoryInterface $academicYearRepository)
    {
        $this->sectorRepository = $sectorRepository;
        $this->academicYearRepository = $academicYearRepository;
    }

    public function updatingYearFilter()
    {
        $this->resetPage();
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
        try {
            $success = $this->sectorRepository->updateOrdering($ids);

            if ($success) {
                Flux::toast(
                    heading: 'Thành công',
                    text: 'Thứ tự ngành sinh hoạt đã được cập nhật.',
                    variant: 'success',
                );
            } else {
                Flux::toast(
                    heading: 'Đã xảy ra lỗi!',
                    text: 'Không thể cập nhật thứ tự ngành sinh hoạt.',
                    variant: 'error',
                );
            }
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Đã xảy ra lỗi!',
                text: 'Lỗi khi cập nhật thứ tự: ' . (app()->environment('local') ? $e->getMessage() : 'Vui lòng thử lại sau.'),
                variant: 'error',
            );
        }

        $this->redirectRoute('admin.management.sectors', ['yearFilter' => $this->yearFilter], navigate: true);
    }
}
