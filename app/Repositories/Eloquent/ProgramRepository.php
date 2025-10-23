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

    public function prepareDate(array &$data): void
    {
        if (isset($data['course'])) {
            $data['course'] = ucwords(trim($data['course']));
        }

        if (isset($data['sector'])) {
            $data['sector'] = ucwords(trim($data['sector']));
        }

        if (isset($data['description'])) {
            $data['description'] = ucfirst(trim($data['description']));
        }
    }
    
}
