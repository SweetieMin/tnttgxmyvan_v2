<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\ProgramRepositoryInterface;
use App\Models\Program;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    public function getIdAndSector()
    {
        return $this->model
            ->select('id', 'sector')
            ->orderBy('ordering')
            ->get();
    }

    public function paginate(int $perPage = 15, array $orderBy = []): LengthAwarePaginator
    {
        return $this->safeExecute(function () use ($perPage, $orderBy) {
            $query = $this->model->newQuery();

            foreach ($orderBy as $col => $dir) {
                $query->orderBy($col, $dir);
            }


            return $query->orderBy('ordering')->paginate($perPage);
        }, 'Không thể tải dữ liệu phân trang.');
    }
}
