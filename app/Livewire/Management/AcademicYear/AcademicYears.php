<?php

namespace App\Livewire\Management\AcademicYear;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;
use Livewire\Attributes\Title;

#[Title('Niên khoá')]
class AcademicYears extends Component
{
    use WithPagination;

    protected AcademicYearRepositoryInterface $academicYearRepository;

    public $search = '';
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }


    public function boot(AcademicYearRepositoryInterface $academicYearRepository)
    {
        $this->academicYearRepository = $academicYearRepository;
    }

    public function render()
    {
        $academic_years = $this->academicYearRepository
            ->paginateWithSearch($this->search, $this->perPage);

        return view('livewire.management.academic-year.academic-years', [
            'academic_years' => $academic_years,
        ]);
    }

    public function addAcademicYear()
    {
        $this->dispatch('addAcademicYear');
    }

    public function editAcademicYear($id){
        $this->dispatch('editAcademicYear', $id);
    }

    public function deleteAcademicYear($id){
        $this->dispatch('deleteAcademicYear', $id);
    }
}
