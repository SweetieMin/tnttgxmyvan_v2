<?php

namespace App\Traits\Management;

use App\Models\Sector;
use App\Models\Program;

trait HandlesSectorForm
{
    protected function resetForm()
    {
        $this->reset([
            'academic_year_id',
            'program_id',
            'sector',
        ]);

        $this->isEditSectorMode = false;

        $this->resetErrorBag();
    }

    /**
     * Khi chọn niên khoá hoặc chương trình → thử sinh tên ngành tự động
     */
    public function updatedAcademicYearId($value)
    {
        if (empty($value)) {
            $this->addError('academic_year_id', 'Vui lòng chọn niên khoá.');
        } else {
            $this->resetErrorBag('academic_year_id');
        }
        $this->checkSectorName();
    }

    public function updatedProgramId($value)
    {
        if (empty($value)) {
            $this->addError('program_id', 'Vui lòng chọn chương trình.');
        } else {
            $this->resetErrorBag('program_id');
        }

        // Lấy sector từ program
        $program = Program::find($value);
        if ($program) {
            $this->sector = $program->sector;
        }
        $this->checkSectorName();
    }

    public function updatedSector($value)
    {
        $this->checkSectorName();
    }

    protected function checkSectorName()
    {
        if (empty($this->academic_year_id) || empty($this->program_id) || empty($this->sector)) {
            return;
        }
        
        $existingSectors = Sector::where('academic_year_id', $this->academic_year_id)
            ->where('program_id', $this->program_id)
            ->where('sector', $this->sector)
            ->exists();

        if ($existingSectors) {
            $this->addError('sector', 'Ngành sinh hoạt này đã tồn tại trong niên khoá.');
            return;
        } else {
            $this->resetErrorBag('sector');
        }
    }
}
