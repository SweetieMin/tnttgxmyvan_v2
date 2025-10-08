<?php

namespace App\Repositories\Eloquent;

use App\Models\AcademicYear;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;

class AcademicYearRepository extends BaseRepository implements AcademicYearRepositoryInterface
{
    public function __construct(AcademicYear $model)
    {
        parent::__construct($model);
    }

    // ✅ Bạn có thể thêm các hàm riêng cho AcademicYear
    public function getOngoingYears()
    {
        return $this->model->where('status_academic', 'ongoing')->get();
    }

    public function getUpcomingYears()
    {
        return $this->model->where('status_academic', 'upcoming')->get();
    }
}
