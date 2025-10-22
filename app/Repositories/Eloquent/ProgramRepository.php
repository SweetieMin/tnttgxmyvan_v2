<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\ProgramRepositoryInterface;
use App\Models\Program;
use App\Repositories\BaseRepository;

class ProgramRepository extends BaseRepository implements ProgramRepositoryInterface
{
    public function __construct(Program $model)
    {
        parent::__construct($model);
    }
}