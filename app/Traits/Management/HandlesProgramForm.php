<?php

namespace App\Traits\Management;

use App\Models\Program;


trait HandlesProgramForm
{

    public bool $statusCourse = false;
    public bool $statusSector = false;
    public bool $canSaveData = false;

    protected function resetForm()
    {
        $this->reset([
            'course',
            'sector',
            'description',
        ]);

        $this->isEditProgramMode = false;
        $this->statusCourse = false;
        $this->statusSector = false;
        $this->canSaveData = false;
        $this->resetErrorBag();
    }

    public function updatedCourse($value)
    {
        if (Program::where('course', $value)->exists()) {
            $this->addError('course', 'Tên Giáo lý này đã tồn tại trong hệ thống.');
            $this->statusCourse = false;
        } else {
            $this->resetErrorBag('course');

            $this->statusCourse = !empty(trim($value));
        }
        $this->checkFormValidity();
    }

    public function updatedSector($value)
    {
        if (Program::where('sector', $value)->exists()) {
            $this->addError('sector', 'Tên Ngành sinh hoạt này đã tồn tại trong hệ thống.');
            $this->statusSector = false;
        } else {
            $this->resetErrorBag('sector');

            $this->statusSector = !empty(trim($value));
        }
        $this->checkFormValidity();
    }

    /**
     * 🔹 Kiểm tra xem form có hợp lệ hay không
     */
    protected function checkFormValidity(): void
    {
        $this->canSaveData = $this->statusSector && $this->statusCourse;
    }
}
