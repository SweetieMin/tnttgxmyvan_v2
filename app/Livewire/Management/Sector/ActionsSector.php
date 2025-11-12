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
    public int $academic_year_id;
    public int $program_id;

    public $sectorID;

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

            session()->flash('success', 'Ngành sinh hoạt tạo thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo ngành sinh hoạt thất bại.' . $e->getMessage());
        }

        $this->redirectRoute('admin.management.sectors', navigate: true);
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
            session()->flash('error', 'Không tìm thấy ngành sinh hoạt');
            return $this->redirectRoute('admin.management.sectors', navigate: true);
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

            session()->flash('success', 'Ngành sinh hoạt cập nhật thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật ngành sinh hoạt thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.sectors', navigate: true);
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
            session()->flash('error', 'Không tìm thấy ngành sinh hoạt');
            return $this->redirectRoute('admin.management.sectors', navigate: true);
        }

    }

    public function deleteSectorConfirm()
    {
        try {
            $this->sectorRepository->delete($this->sectorID);

            session()->flash('success', 'Ngành sinh hoạt xoá thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Xoá ngành sinh hoạt thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.sectors', navigate: true);
    }
}
