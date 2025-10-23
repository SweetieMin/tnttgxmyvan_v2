<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Models\Course;
use App\Repositories\BaseRepository;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function courseWithSearchAndYear(?string $search = null, ?int $year = 1)
    {
        $query = $this->model->query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($year) {
            $query->where(function ($q) use ($year) {
                $q->where('academic_year_id', $year);
            });
        }

        return $query->orderBy('ordering')->paginate(15);
    }
}
