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

        $years = $this->academicYearRepository->getAcademicOngoingAndFinished();

        return view('livewire.management.course.courses', [
            'courses' => $courses,
            'years' => $years,
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
}
