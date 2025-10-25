<?php

namespace App\Livewire\Management\Course;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;

#[Title('Lớp Giáo Lý')]
class Courses extends Component
{
    use WithPagination;

    protected CourseRepositoryInterface $courseRepository;

    protected AcademicYearRepositoryInterface $academicYearRepository;

    public $search = '';

    public $yearFilter = null;

    public function mount()
    {
        $ongoing = $this->academicYearRepository->getAcademicOngoingNow();
        $this->yearFilter = $ongoing?->id;
    }


    public function boot(CourseRepositoryInterface $courseRepository, AcademicYearRepositoryInterface $academicYearRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->academicYearRepository = $academicYearRepository;
    }

    public function render()
    {
        $courses = $this->courseRepository->courseWithSearchAndYear($this->search, $this->yearFilter);

        $years = $this->academicYearRepository->all();

        return view('livewire.management.course.courses', [
            'courses' => $courses,
            'years' => $years,
            'selectedYear' => $this->yearFilter,
        ]);
    }

    public function updatingYearFilter()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function addCourse()
    {
        $this->dispatch('addCourse');
    }

    public function editCourse($id)
    {
        $this->dispatch('editCourse', $id);
    }

    public function deleteCourse($id)
    {
        $this->dispatch('deleteCourse', $id);
    }

    /**
     * Cập nhật ordering sau drag-drop
     */
    public function updateCourseOrdering(array $orderedIds, int $academicYearId)
    {
        try {
            $success = $this->courseRepository->updateCourseOrdering($orderedIds, $academicYearId);
            
            if ($success) {
                session()->flash('success', 'Thứ tự lớp học đã được cập nhật.');
            } else {
                session()->flash('error', 'Không thể cập nhật thứ tự lớp học.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi khi cập nhật thứ tự: ' . $e->getMessage());
        }
        $this->redirectRoute('admin.management.courses', navigate: true);
    }
}
