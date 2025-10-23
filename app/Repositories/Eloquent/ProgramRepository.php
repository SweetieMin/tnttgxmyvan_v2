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

    protected function prepareData(array $data): array
    {

        return [
            'course' => ucwords(trim($data['course']) ?? ''),
            'sector' => ucwords(trim($data['sector']) ?? ''),
            'description' => ucfirst(trim($data['description'] ?? '')),
        ];

    }

    public function getIdAndCourse()
    {
        return $this->model
            ->select('id', 'course')
            ->orderBy('ordering')
            ->get();
    }
}
