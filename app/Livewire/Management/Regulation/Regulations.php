<?php

namespace App\Livewire\Management\Regulation;

use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
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

    public function boot(RegulationRepositoryInterface $regulationRepository, AcademicYearRepositoryInterface $academicYearRepository)
    {
        $this->regulationRepository = $regulationRepository;
        $this->academicYearRepository = $academicYearRepository;
    }

    #[Computed]
    public function regulations()
    {
        return $this->regulationRepository
            ->regulationWithSearchAndYear($this->search, $this->yearFilter);
    }

    #[Computed]
    public function years()
    {
        return $this->academicYearRepository->all();
    }

    public function render()
    {
        return view('livewire.management.regulation.regulations', [
            'regulations' => $this->regulations,
            'years' => $this->years,
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
                Flux::toast(
                    heading: 'Thành công',
                    text: 'Thứ tự nội quy đã được cập nhật.',
                    variant: 'success',
                );
            } else {
                Flux::toast(
                    heading: 'Đã xảy ra lỗi!',
                    text: 'Không thể cập nhật thứ tự nội quy.',
                    variant: 'error',
                );
            }
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Đã xảy ra lỗi!',
                text: app()->environment('local')
                    ? ('Lỗi khi cập nhật thứ tự: ' . $e->getMessage())
                    : 'Rất tiếc, hệ thống không thể cập nhật thứ tự nội quy.',
                variant: 'error',
            );
        }
        $this->redirectRoute('admin.management.regulations', ['yearFilter' => $this->yearFilter], navigate: true);
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
            Flux::toast(
                heading: 'Không tìm thấy!',
                text: 'Không tìm thấy nội quy.',
                variant: 'error',
            );
            return $this->redirectRoute('admin.management.regulations', ['yearFilter' => $this->yearFilter], navigate: true);
        }
    }

    public function deleteRegulationConfirm()
    {
        try {
            $this->regulationRepository->delete($this->regulationID);

            Flux::toast(
                heading: 'Thành công!',
                text: 'Nội quy đã được xoá khỏi hệ thống.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Xoá thất bại!',
                text: 'Không thể xoá nội quy. ' . (app()->environment('local') ? $e->getMessage() : 'Vui lòng thử lại sau.'),
                variant: 'error',
            );
        }

        $this->redirectRoute('admin.management.regulations', ['yearFilter' => $this->yearFilter], navigate: true);
    }
}
