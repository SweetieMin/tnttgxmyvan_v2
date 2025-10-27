<?php

namespace App\Livewire\Management\Regulation;

use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Repositories\Interfaces\RegulationRepositoryInterface;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;

#[Title('Nội quy')]
class Regulations extends Component
{
    use WithPagination;

    protected RegulationRepositoryInterface $regulationRepository;

    protected AcademicYearRepositoryInterface $academicYearRepository;

    public $search = '';

    public $yearFilter = null;

    public $regulationID;

    public function mount()
    {
        $ongoing = $this->academicYearRepository->getAcademicOngoingNow();
        $this->yearFilter = $ongoing?->id;
    }

    public function boot(RegulationRepositoryInterface $regulationRepository, AcademicYearRepositoryInterface $academicYearRepository)
    {
        $this->regulationRepository = $regulationRepository;
        $this->academicYearRepository = $academicYearRepository;
    }

    public function render()
    {
        $regulations = $this->regulationRepository
            ->regulationWithSearchAndYear($this->search, $this->yearFilter);

            $years = $this->academicYearRepository->all();

        return view('livewire.management.regulation.regulations', [
            'regulations' => $regulations,
            'years' => $years,
            'selectedYear' => $this->yearFilter,
        ]);
    }

    public function addRegulation()
    {
        $this->redirectRoute('admin.management.regulations.action', ['parameter' => 'addRegulation'], navigate: true);
    }

    public function editRegulation($id){
        $this->redirectRoute('admin.management.regulations.action', ['parameter' => 'editRegulation', 'regulationID' => $id], navigate: true);
    }

    public function updateRegulationOrdering(array $orderedIds, int $academicYearId)
    {
        try {
            $success = $this->regulationRepository->updateRegulationOrdering($orderedIds, $academicYearId);
            
            if ($success) {
                session()->flash('success', 'Thứ tự nội quy đã được cập nhật.');
            } else {
                session()->flash('error', 'Không thể cập nhật thứ tự nội quy.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi khi cập nhật thứ tự: ' . $e->getMessage());
        }
        $this->redirectRoute('admin.management.regulations', navigate: true);
    }

    public function deleteRegulation($id)
    {

        $regulation = $this->regulationRepository->find($id);

        if ($regulation) {
            // Gán dữ liệu vào form
            $this->regulationID = $regulation->id;

            // Hiển thị modal
            Flux::modal('delete-regulation')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy regulation');
            return $this->redirectRoute('admin.management.regulations', navigate: true);
        }
    }

    public function deleteRegulationConfirm()
    {
        try {
            $this->regulationRepository->delete($this->regulationID);

            session()->flash('success', 'Regulation xoá thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Xoá regulation thất bại.' . $e->getMessage());
        }

        $this->redirectRoute('admin.management.regulations', navigate: true);
    }
}
