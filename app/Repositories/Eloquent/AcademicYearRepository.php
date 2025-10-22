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

    public function paginateWithSearch(?string $search = null, int $perPage = 10)
    {
        $query = $this->model->query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        return $query->orderByDesc('id')->paginate($perPage);
    }
}