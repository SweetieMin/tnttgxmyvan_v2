<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\AcademicYearRepositoryInterface;
use App\Models\AcademicYear;
use App\Repositories\BaseRepository;

class AcademicYearRepository extends BaseRepository implements AcademicYearRepositoryInterface
{
    public function __construct(AcademicYear $model)
    {
        parent::__construct($model);
    }
}