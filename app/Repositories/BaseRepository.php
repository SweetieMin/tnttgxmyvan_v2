<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 15)
    {
        return $this->model->paginate($perPage);
    }

    public function with(array $relations)
    {
        return $this->model->with($relations);
    }

    public function allWith(array $relations = [])
    {
        return $this->model->with($relations)->get();
    }

    public function paginateWith(array $relations = [], int $perPage = 15)
    {
        return $this->model->with($relations)->paginate($perPage);
    }

    public function find(int|string $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        // ✅ Nếu model có cột ordering → auto tăng
        if (Schema::hasColumn($this->model->getTable(), 'ordering') && !isset($data['ordering'])) {
            // Nếu model có cột academic_year_id hoặc season_id thì group theo đó
            $groupKey = collect(['academic_year_id', 'season_id', 'location_id'])
                ->first(fn ($key) => array_key_exists($key, $data));

            $query = $this->model->newQuery();

            if ($groupKey) {
                $query->where($groupKey, $data[$groupKey]);
            }

            $data['ordering'] = ($query->max('ordering') ?? 0) + 1;
        }

        return $this->model->create($data);
    }


    public function update(int|string $id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete(int|string $id)
    {
        return $this->model->destroy($id);
    }
}
