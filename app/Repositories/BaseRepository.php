<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository
{
    protected Model $model;

    /**
     * Khởi tạo repository với model tương ứng
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Lấy tất cả bản ghi
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Tìm bản ghi theo ID
     */
    public function find(int|string $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Phân trang
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
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

    public function paginateWith(array $relations = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with($relations)->paginate($perPage);
    }

    /**
     * Tạo bản ghi mới
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Cập nhật bản ghi theo ID
     */
    public function update(int|string $id, array $data): bool
    {
        $record = $this->find($id);
        if (! $record) {
            return false;
        }

        return $record->update($data);
    }

    /**
     * Xóa bản ghi theo ID
     */
    public function delete(int|string $id): bool
    {
        $record = $this->find($id);
        if (! $record) {
            return false;
        }

        return (bool) $record->delete();
    }
}
