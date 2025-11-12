<?php

namespace App\Traits\Management;

use App\Models\Program;
use App\Services\CourseSectorNameService;

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
     * Khi chọn niên khoá → kiểm tra lại tên ngành.
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
        if ($program && !empty($program->sector)) {
            $service = $this->sectorNameService();
            $baseName = $program->sector;

            $this->sector = $service->generateUniqueSectorName(
                $baseName,
                $this->academic_year_id,
                $this->program_id,
                $this->sectorID ?? null
            );
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

        $service = $this->sectorNameService();

        $uniqueName = $service->generateUniqueSectorName(
            $this->sector,
            $this->academic_year_id,
            $this->program_id,
            $this->sectorID ?? null
        );

        if ($uniqueName !== $this->sector) {
            $this->sector = $uniqueName;
        } else {
            $this->resetErrorBag('sector');
        }
    }

    protected function sectorNameService(): CourseSectorNameService
    {
        return app(CourseSectorNameService::class);
    }
}
