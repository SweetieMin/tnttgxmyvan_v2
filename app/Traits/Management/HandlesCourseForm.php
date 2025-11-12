<?php

namespace App\Traits\Management;

use App\Services\CourseSectorNameService;

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
     * Khi chọn niên khoá → kiểm tra lại tên lớp.
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
        if (empty($value)) {
            $this->addError('program_id', 'Vui lòng chọn chương trình.');
        } else {
            $this->resetErrorBag('program_id');

            $service = $this->courseNameService();
            $baseName = $service->generateCourseName($this->program_id);

            if (! empty($baseName)) {
                $this->course = $service->generateUniqueCourseName(
                    $baseName,
                    $this->academic_year_id,
                    $this->program_id,
                    $this->courseID ?? null
                );
            }
        }

        $this->checkCourseName();
    }

    public function updatedCourse($value)
    {
        $this->checkCourseName();
    }

    protected function checkCourseName()
    {
        if (empty($this->academic_year_id) || empty($this->program_id) || empty($this->course)) {
            return;
        }

        $service = $this->courseNameService();

        $uniqueName = $service->generateUniqueCourseName(
            $this->course,
            $this->academic_year_id,
            $this->program_id,
            $this->courseID ?? null
        );

        if ($uniqueName !== $this->course) {
            $this->course = $uniqueName;
        } else {
            $this->resetErrorBag('course');
        }
    }

    protected function courseNameService(): CourseSectorNameService
    {
        return app(CourseSectorNameService::class);
    }
}
