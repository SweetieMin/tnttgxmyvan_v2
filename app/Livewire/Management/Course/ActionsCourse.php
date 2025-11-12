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
    public ?int $academic_year_id = null;
    public ?int $program_id = null;

    public ?int $courseID = null;


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
            // Kiểm tra course đã tồn tại trong năm học chưa
            if ($this->courseRepository->existsInAcademicYear($data['course'], $data['academic_year_id'])) {
                $this->addError('course', 'Lớp này đã tồn tại trong niên khoá.');
                return;
            }

            $this->courseRepository->create($data);

            Flux::toast(
                heading: 'Thành công',
                text: 'Lớp giáo lý mới đã được tạo.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Đã xảy ra lỗi!',
                text: 'Không thể tạo lớp giáo lý. ' . (app()->environment('local') ? $e->getMessage() : 'Vui lòng thử lại sau.'),
                variant: 'error',
            );
        }

        $this->redirectRoute('admin.management.courses', ['yearFilter' => $this->academic_year_id], navigate: true);
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
            Flux::toast(
                heading: 'Không tìm thấy!',
                text: 'Không tìm thấy lớp giáo lý.',
                variant: 'error',
            );
            return $this->redirectRoute('admin.management.courses', ['yearFilter' => $this->academic_year_id], navigate: true);
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
            // Kiểm tra course đã tồn tại trong năm học chưa (trừ chính nó)
            if ($this->courseRepository->existsInAcademicYear($data['course'], $data['academic_year_id'], $this->courseID)) {
                $this->addError('course', 'Lớp này đã tồn tại trong niên khoá.');
                return;
            }

            $this->courseRepository->update($this->courseID, $data);

            Flux::toast(
                heading: 'Đã lưu thay đổi.',
                text: 'Lớp giáo lý cập nhật thành công.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Cập nhật thất bại!',
                text: 'Không thể cập nhật lớp giáo lý. ' . (app()->environment('local') ? $e->getMessage() : 'Vui lòng thử lại sau.'),
                variant: 'error',
            );
        }

        $this->redirectRoute('admin.management.courses', ['yearFilter' => $this->academic_year_id], navigate: true);
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
            Flux::toast(
                heading: 'Không tìm thấy!',
                text: 'Không tìm thấy lớp giáo lý.',
                variant: 'error',
            );
            return $this->redirectRoute('admin.management.courses', ['yearFilter' => $this->academic_year_id], navigate: true);
        }
    }

    public function deleteCourseConfirm()
    {
        try {
            $this->courseRepository->delete($this->courseID);

            Flux::toast(
                heading: 'Thành công!',
                text: 'Lớp giáo lý đã được xoá.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Xoá thất bại!',
                text: 'Không thể xoá lớp giáo lý. ' . (app()->environment('local') ? $e->getMessage() : 'Vui lòng thử lại sau.'),
                variant: 'error',
            );
        }

        $this->redirectRoute('admin.management.courses', ['yearFilter' => $this->academic_year_id], navigate: true);
    }

}
