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
}
