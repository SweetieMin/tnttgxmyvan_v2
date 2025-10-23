<?php

namespace App\Livewire\Management\Course;

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Validation\Management\CourseRules;
use App\Traits\Management\HandlesCourseForm;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\ProgramRepositoryInterface;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;


class ActionsCourse extends Component
{
    use HandlesCourseForm;

    protected CourseRepositoryInterface $courseRepository;

    protected AcademicYearRepositoryInterface $academicYearRepository;

    protected ProgramRepositoryInterface $programRepository;

    public $isEditCourseMode = false;

    public string $course;
    public int $academic_year_id;
    public int $program_id;

    public $courseID;


    /**
     * Quy tắc xác thực
     */
    protected function rules()
    {
        return CourseRules::rules($this->courseID, $this->academic_year_id, $this->program_id);
    }

    /**
     * Thông báo lỗi xác thực (tiếng Việt)
     */
    protected function messages()
    {
        return CourseRules::messages();
    }

    public function boot(CourseRepositoryInterface $courseRepository, AcademicYearRepositoryInterface $academicYearRepository, ProgramRepositoryInterface $programRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->academicYearRepository = $academicYearRepository;
        $this->programRepository = $programRepository;
    }

    public function render()
    {
        $years = $this->academicYearRepository->getAcademicOngoingAndUpcoming();
        $programs = $this->programRepository->getIdAndCourse();

        return view('livewire.management.course.actions-course', [
            'years' => $years,
            'programs' => $programs,
        ]);
    }

    #[On('addCourse')]
    public function addCourse()
    {
        $this->resetForm();
        Flux::modal('action-course')->show();
    }

    public function createCourse()
    {
        $this->validate();

        $data = $this->only([
            'academic_year_id',
            'program_id',
            'course',
        ]);

        try {
            $this->courseRepository->create($data);

            session()->flash('success', 'Lớp giáo lý tạo thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo lớp giáo lý thất bại.' . $e->getMessage());
        }

        $this->redirectRoute('admin.management.courses', navigate: true);
    }

    #[On('editCourse')]
    public function editCourse($id)
    {
        $this->resetForm();

        $course = $this->courseRepository->find($id);

        if ($course) {
            // Gán dữ liệu vào form
            $this->courseID = $course->id;
            $this->isEditCourseMode = true;

            $this->academic_year_id = $course->academic_year_id ;
            $this->program_id = $course->program_id ;
            $this->course = $course->course ;

            // Hiển thị modal
            Flux::modal('action-course')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy course');
            return $this->redirectRoute('admin.management.courses', navigate: true);
        }
    }

    public function updateCourse()
    {
        $this->validate();

        $data = $this->only([
            'academic_year_id',
            'program_id',
            'course',
        ]);

        try {
            $this->courseRepository->update($this->courseID, $data);

            session()->flash('success', 'Course cập nhật thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật course thất bại.' . $e->getMessage());
        }

        $this->redirectRoute('admin.management.courses', navigate: true);
    }

    #[On('deleteCourse')]
    public function deleteCourse($id)
    {

        $this->resetForm();

        $course = $this->courseRepository->find($id);

        if ($course) {
            // Gán dữ liệu vào form
            $this->courseID = $course->id;
            // Hiển thị modal
            Flux::modal('delete-course')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy course');
            return $this->redirectRoute('admin.management.courses', navigate: true);
        }
    }

    public function deleteCourseConfirm()
    {
        try {
            $this->courseRepository->delete($this->courseID);

            session()->flash('success', 'Course xoá thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Xoá course thất bại.' . $e->getMessage());
        }

        $this->redirectRoute('admin.management.courses', navigate: true);
    }
}
