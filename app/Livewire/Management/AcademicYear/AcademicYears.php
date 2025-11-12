<?php

namespace App\Livewire\Management\AcademicYear;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

#[Title('Niên khoá')]
class AcademicYears extends Component
{
    use WithPagination;

    protected AcademicYearRepositoryInterface $academicYearRepository;

    public $search = '';
    public $perPage = 10;

    public function boot(AcademicYearRepositoryInterface $academicYearRepository)
    {
        $this->academicYearRepository = $academicYearRepository;
    }

    #[Computed]
    public function academicYears()
    {
        return $this->academicYearRepository
            ->paginateWithSearch($this->search, $this->perPage);
    }

    public function addAcademicYear()
    {
        $this->dispatch('addAcademicYear');
    }

    public function editAcademicYear(int $id)
    {
        $this->dispatch('editAcademicYear', $id);
    }

    public function deleteAcademicYear(int $id)
    {
        $this->dispatch('deleteAcademicYear', $id);
    }

    public function render()
    {
        return view('livewire.management.academic-year.academic-years', [
            'academic_years' => $this->academicYears,
        ]);
    }
}
