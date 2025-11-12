<?php

namespace App\Livewire\Management\Sector;

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Validation\Management\SectorRules;
use App\Traits\Management\HandlesSectorForm;
use App\Repositories\Interfaces\SectorRepositoryInterface;
use App\Repositories\Interfaces\ProgramRepositoryInterface;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;


class ActionsSector extends Component
{
    use HandlesSectorForm;

    protected SectorRepositoryInterface $sectorRepository;
    protected AcademicYearRepositoryInterface $academicYearRepository;
    protected ProgramRepositoryInterface $programRepository;

    public $isEditSectorMode = false;

    public string $sector = '';

    public ?int $academic_year_id = null;
    public ?int $program_id = null;

    public ?int $sectorID = null;

    /**
     * Quy tắc xác thực
     */
    protected function rules()
    {
        return SectorRules::rules($this->sectorID, $this->academic_year_id, $this->program_id);
    }

    /**
     * Thông báo lỗi xác thực (tiếng Việt)
     */
    protected function messages()
    {
        return SectorRules::messages();
    }

    public function boot(SectorRepositoryInterface $sectorRepository, AcademicYearRepositoryInterface $academicYearRepository, ProgramRepositoryInterface $programRepository)
    {
        $this->sectorRepository = $sectorRepository;
        $this->academicYearRepository = $academicYearRepository;
        $this->programRepository = $programRepository;
    }

    public function render()
    {
        $years = $this->academicYearRepository->getAcademicOngoingAndUpcoming();
        $programs = $this->programRepository->getIdAndSector();

        return view('livewire.management.sector.actions-sector', [
            'years' => $years,
            'programs' => $programs,
        ]);
    }

    #[On('addSector')]
    public function addSector()
    {
        $this->resetForm();
        Flux::modal('action-sector')->show();
    }

    public function createSector()
    {
        $this->validate();

        $data = $this->only([
            'academic_year_id',
            'program_id',
            'sector',
        ]);

        try {
            $this->sectorRepository->create($data);

            Flux::toast(
                heading: 'Thành công',
                text: 'Ngành sinh hoạt mới đã được tạo.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Đã xảy ra lỗi!',
                text: 'Không thể tạo ngành sinh hoạt. ' . (app()->environment('local') ? $e->getMessage() : 'Vui lòng thử lại sau.'),
                variant: 'error',
            );
        }

        $this->redirectRoute('admin.management.sectors', ['yearFilter' => $this->academic_year_id], navigate: true);
    }

    #[On('editSector')]
    public function editSector($id)
    {
        $this->resetForm();

        $sector = $this->sectorRepository->find($id);

        if ($sector) {
            // Gán dữ liệu vào form
            $this->sectorID = $sector->id;
            $this->academic_year_id = $sector->academic_year_id;
            $this->program_id = $sector->program_id;
            $this->sector = $sector->sector;
            $this->isEditSectorMode = true;

            // Hiển thị modal
            Flux::modal('action-sector')->show();
        } else {
            // Nếu không tìm thấy
            Flux::toast(
                heading: 'Không tìm thấy!',
                text: 'Không tìm thấy ngành sinh hoạt.',
                variant: 'error',
            );
            return $this->redirectRoute('admin.management.sectors', ['yearFilter' => $this->academic_year_id], navigate: true);
        }

    }

    public function updateSector()
    {
        $this->validate();

        $data = $this->only([
            'academic_year_id',
            'program_id',
            'sector',
        ]);

        try {
            $this->sectorRepository->update($this->sectorID, $data);

            Flux::toast(
                heading: 'Đã lưu thay đổi.',
                text: 'Ngành sinh hoạt cập nhật thành công.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Cập nhật thất bại!',
                text: 'Không thể cập nhật ngành sinh hoạt. ' . (app()->environment('local') ? $e->getMessage() : 'Vui lòng thử lại sau.'),
                variant: 'error',
            );
        }
        
        $this->redirectRoute('admin.management.sectors', ['yearFilter' => $this->academic_year_id], navigate: true);
    }

    #[On('deleteSector')]
    public function deleteSector($id)
    {

        $this->resetForm();

        $sector = $this->sectorRepository->find($id);

        if ($sector) {
            // Gán dữ liệu vào form
            $this->sectorID = $sector->id;
                
            // Hiển thị modal
            Flux::modal('delete-sector')->show();
        } else {
            // Nếu không tìm thấy
            Flux::toast(
                heading: 'Không tìm thấy!',
                text: 'Không tìm thấy ngành sinh hoạt.',
                variant: 'error',
            );
            return $this->redirectRoute('admin.management.sectors', ['yearFilter' => $this->academic_year_id], navigate: true);
        }

    }

    public function deleteSectorConfirm()
    {
        try {
            $this->sectorRepository->delete($this->sectorID);

            Flux::toast(
                heading: 'Thành công!',
                text: 'Ngành sinh hoạt đã được xoá.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Xoá thất bại!',
                text: 'Không thể xoá ngành sinh hoạt. ' . (app()->environment('local') ? $e->getMessage() : 'Vui lòng thử lại sau.'),
                variant: 'error',
            );
        }
        
        $this->redirectRoute('admin.management.sectors', ['yearFilter' => $this->academic_year_id], navigate: true);
    }
}
