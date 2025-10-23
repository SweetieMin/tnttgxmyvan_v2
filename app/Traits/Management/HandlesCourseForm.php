<?php

namespace App\Traits\Management;

use App\Models\Course;
use App\Models\Program;


trait HandlesCourseForm
{
    protected function resetForm()
    {
        $this->reset([
            'academic_year_id',
            'program_id',
            'course',
        ]);

        $this->isEditCourseMode = false;

        $this->resetErrorBag();
    }


    /**
     * Khi chọn niên khoá hoặc chương trình → thử sinh tên lớp tự động
     */
    public function updatedAcademicYearId($value)
    {
        if (empty($value)) {
            $this->addError('academic_year_id', 'Vui lòng chọn niên khoá.');
        } else {
            $this->resetErrorBag('academic_year_id');
        }
        $this->checkCourseName();
    }

    public function updatedProgramId($value)
    {

        $course = $this->courseRepository->find($value);

        if (empty($value)) {
            $this->addError('program_id', 'Vui lòng chọn chương trình.');
        } else {
            $this->resetErrorBag('program_id');
        }

        $this->course = $course->course;
        $this->checkCourseName();
    }

    public function updatedCourse($value)
    {

        $this->checkCourseName();
    }

    protected function checkCourseName()
    {

        if (empty($this->academic_year_id) || empty($this->program_id) ||  empty($this->course)) {
            return;
        }
        $existingCourses = Course::where('academic_year_id', $this->academic_year_id)
            ->where('program_id', $this->program_id)
            ->where('course', $this->course)
            ->exists();

        // Nếu chưa có lớp nào => dùng tên gốc
        if ($existingCourses) {

            $this->addError('course', 'Lớp này đã tồn tại trong niên khoá.');
            return;
        } else {
            $this->resetErrorBag('course');
        }
    }
}
