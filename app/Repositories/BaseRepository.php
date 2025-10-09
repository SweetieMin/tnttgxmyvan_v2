<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
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
