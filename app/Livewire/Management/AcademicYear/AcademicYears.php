<?php

namespace App\Livewire\Management\AcademicYear;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;

class AcademicYears extends Component
{
    use WithPagination;

    public $academic_years = [];

    public function render()
    {
        $academic_years = [];

        return view('livewire.management.academic-year.academic-years', [
            'academic_years' => $academic_years,
        ]);
    }
}
