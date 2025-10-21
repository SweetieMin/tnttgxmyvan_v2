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
}