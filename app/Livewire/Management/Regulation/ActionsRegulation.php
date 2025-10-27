<?php

namespace App\Livewire\Management\Regulation;

use Flux\Flux;

use Livewire\Component;

use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;
use App\Validation\Management\RegulationRules;
use App\Traits\Management\HandlesRegulationForm;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\RegulationRepositoryInterface;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;

#[Title('Nội quy')]
class ActionsRegulation extends Component
{
    use HandlesRegulationForm;

    protected RegulationRepositoryInterface $regulationRepository;

    protected RoleRepositoryInterface $roleRepository;

    protected AcademicYearRepositoryInterface $academicYearRepository;

    public $isEditRegulationMode = false;

    public $roles;

    public $regulationID;

    public $years;

    public $academic_year_id;


    public $type = 'plus';
    public $points;
    public $description;

    public array $regulationApplyRole = [];


    /**
     * Quy tắc xác thực
     */
    protected function rules()
    {
        return RegulationRules::rules($this->regulationID);
    }

    /**
     * Thông báo lỗi xác thực (tiếng Việt)
     */
    protected function messages()
    {
        return RegulationRules::messages();
    }

    public function boot(RegulationRepositoryInterface $regulationRepository, RoleRepositoryInterface $roleRepository,  AcademicYearRepositoryInterface $academicYearRepository)
    {
        $this->regulationRepository = $regulationRepository;
        $this->roleRepository = $roleRepository;
        $this->academicYearRepository = $academicYearRepository;
    }

    public function loadData()
    {
        $this->roles = $this->roleRepository->all();
        $this->years = $this->academicYearRepository->getAcademicOngoingAndUpcoming();
    }

    public function mount()
    {
        $parameter = request()->input('parameter');
        $regulationID    = request()->input('regulationID');

        $this->isEditRegulationMode = $parameter === 'editRegulation';
        $this->regulationID = $this->isEditRegulationMode ? $regulationID : null;

        if ($this->isEditRegulationMode) {
            $this->editRegulation($this->regulationID);
        } else {
            $this->addRegulation();
        }

        $this->loadData();

    }

    public function render()
    {
        return view('livewire.management.regulation.actions-regulation');
    }

    public function backRegulation()
    {
        $this->redirectRoute('admin.management.regulations', navigate: true);
    }

    public function addRegulation()
    {
        $this->resetForm();
        $this->academic_year_id = $this->academicYearRepository
        ->getAcademicOngoingAndUpcoming()->first()->id;
    }

    public function createRegulation()
    {
        //$this->validate();

        $data = $this->only([
            'type',
            'academic_year_id',
            'description',
            'points'
        ]);

        try {
            $regulation = $this->regulationRepository->create($data);

            if (!empty($this->regulationRepository)) {
                $regulation->regulationApplyRole()->sync($this->regulationApplyRole);
            }

            session()->flash('success', 'Regulation tạo thành công.');
        } catch (\Exception $e) {

            Log::error('Lỗi khi tạo regulation: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'Tạo regulation thất bại.' . $e->getMessage());
        }

        $this->redirectRoute('admin.management.regulations', navigate: true);
    }

    public function editRegulation($id)
    {
        //$this->resetForm();

        $regulation = $this->regulationRepository->find($id);

        if ($regulation) {
            // Gán dữ liệu vào form
            $this->regulationID = $regulation->id;
            $this->isEditRegulationMode = true;

            $this->academic_year_id = $regulation->academic_year_id;
            $this->type = $regulation->type;
            $this->description = $regulation->description;
            $this->points = $regulation->points;

            $this->regulationApplyRole = $regulation->roles()->pluck('roles.id')->toArray();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy regulation');
            return $this->redirectRoute('admin.management.regulations', navigate: true);
        }
    }

    public function updateRegulation()
    {
        //$this->validate();

        $data = $this->only([
            'type',
            'academic_year_id',
            'description',
            'points'
        ]);

        try {
            $this->regulationRepository->update($this->regulationID, $data);

            $regulation = $this->regulationRepository->find($this->regulationID);

            if (!empty($this->regulationRepository)) {
                $regulation->regulationApplyRole()->sync($this->regulationApplyRole);
            }

            session()->flash('success', 'Regulation cập nhật thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật regulation thất bại.' . $e->getMessage());
        }

        $this->redirectRoute('admin.management.regulations', navigate: true);
    }


}
