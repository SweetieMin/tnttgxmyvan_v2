<?php

namespace App\Livewire\Management\AcademicYear;

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Validation\Management\AcademicYearRules;
use App\Traits\Management\HandlesAcademicYearForm;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;


class ActionsAcademicYear extends Component
{
    use HandlesAcademicYearForm;

    protected AcademicYearRepositoryInterface $academicYearRepository;

    public $isEditAcademicYearMode = false;

    public string $name = '';
    public ?string $catechism_start_date = null;
    public ?string $catechism_end_date = null;
    public ?float $catechism_avg_score = null;
    public ?float $catechism_training_score = null;
    public ?string $activity_start_date = null;
    public ?string $activity_end_date = null;
    public ?float $activity_score = null;
    public string $status_academic = 'upcoming';

    public $academicYearID;

    /**
     * Quy tắc xác thực
     */
    protected function rules()
    {
        return AcademicYearRules::rules($this->academicYearID);
    }

    /**
     * Thông báo lỗi xác thực (tiếng Việt)
     */
    protected function messages()
    {
        return AcademicYearRules::messages();
    }

    public function boot(AcademicYearRepositoryInterface $academicYearRepository)
    {
        $this->academicYearRepository = $academicYearRepository;
    }


    public function render()
    {
        return view('livewire.management.academic-year.actions-academic-year');
    }

    #[On('addAcademicYear')]
    public function addAcademicYear()
    {
        $this->resetForm();
        Flux::modal('action-academic-year')->show();
    }

    public function createAcademicYear()
    {
        $this->validate();

        $data = $this->only([
            'name',
            'catechism_start_date',
            'catechism_end_date',
            'catechism_avg_score',
            'catechism_training_score',
            'activity_start_date',
            'activity_end_date',
            'activity_score',
            'status_academic',
        ]);

        try {
            $this->academicYearRepository->create($data);

            session()->flash('success', 'Niên khoá tạo thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo niên khoá thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.academic-year', navigate: true);
    }

    #[On('editAcademicYear')]
    public function editAcademicYear($id)
    {
        $this->resetForm();

        $academicYear = $this->academicYearRepository->find($id);

        if ($academicYear) {
            // Gán dữ liệu vào form
            $this->academicYearID = $academicYear->id;
            $this->isEditAcademicYearMode = true;
    
            $this->name                     = $academicYear->name;
            $this->catechism_start_date     = $academicYear->formatted_catechism_start_date;
            $this->catechism_end_date       = $academicYear->formatted_catechism_end_date;
            $this->catechism_avg_score      = $academicYear->catechism_avg_score;
            $this->catechism_training_score = $academicYear->catechism_training_score;
            $this->activity_start_date      = $academicYear->formatted_activity_start_date;
            $this->activity_end_date        = $academicYear->formatted_activity_end_date;
            $this->activity_score           = $academicYear->activity_score;
            $this->status_academic          = $academicYear->status_academic;

            $this->checkOngoingStatus();
            // Hiển thị modal
            Flux::modal('action-academic-year')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy niên khoá');
            return $this->redirectRoute('admin.management.academic-year', navigate: true);
        }

    }

    public function updateAcademicYear()
    {
        $this->validate();

        $data = $this->only([
            'name',
            'catechism_start_date',
            'catechism_end_date',
            'catechism_avg_score',
            'catechism_training_score',
            'activity_start_date',
            'activity_end_date',
            'activity_score',
            'status_academic',
        ]);

        try {
            $this->academicYearRepository->update($this->academicYearID,$data);

            session()->flash('success', 'Niên khoá cập nhật thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật niên khoá thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.academic-year', navigate: true);
    }

    #[On('deleteAcademicYear')]
    public function deleteAcademicYear($id)
    {
        $academicYear = $this->academicYearRepository->find($id);

        if ($academicYear) {
            // Gán dữ liệu vào form
            $this->academicYearID = $academicYear->id;
                
            // Hiển thị modal
            Flux::modal('delete-academic-year')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy niên khoá');
            return $this->redirectRoute('admin.management.academic-year', navigate: true);
        }

    }

    public function deleteAcademicYearConfirm()
    {
        try {
            $this->academicYearRepository->delete($this->academicYearID);

            session()->flash('success', 'Niên khoá xoá thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Xoá niên khoá thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.academic-year', navigate: true);
    }
}
