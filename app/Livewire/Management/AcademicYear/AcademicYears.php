<?php

namespace App\Livewire\Management\AcademicYear;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;

class AcademicYears extends Component
{
    protected AcademicYearRepositoryInterface $academicYearRepository;
    use WithPagination;

    public function boot(AcademicYearRepositoryInterface $academicYearRepository)
    {
        $this->academicYearRepository = $academicYearRepository;
    }

    public function render()
    {
        // Gọi repository để lấy danh sách có phân trang
        $academic_years = $this->academicYearRepository
            ->paginate(10); // hoặc có thể dùng hàm custom trong repo

        return view('livewire.management.academic-year.academic-years', [
            'academic_years' => $academic_years,
        ]);
    }
}
