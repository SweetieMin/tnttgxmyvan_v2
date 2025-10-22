<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Exception;

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
     * Thực thi an toàn có bắt lỗi và log
     */
    protected function safeExecute(callable $callback, string $errorMessage)
    {
        try {
            return $callback();
        } catch (Exception $e) {
            Log::error($errorMessage . ' | ' . $e->getMessage(), [
                'repository' => static::class,
                'model' => get_class($this->model),
            ]);
            throw new Exception($errorMessage);
        }
    }

    /**
     * Lấy tất cả bản ghi
     */
    public function all(): Collection
    {
        return $this->safeExecute(
            fn() => $this->model->all(),
            'Không thể lấy danh sách bản ghi.'
        );
    }

    /**
     * Tìm bản ghi theo ID
     */
    public function find(int|string $id): ?Model
    {
        return $this->safeExecute(
            fn() => $this->model->find($id),
            'Không thể tìm bản ghi có ID ' . $id
        );
    }

    /**
     * Phân trang
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->safeExecute(
            fn() => $this->model->paginate($perPage),
            'Không thể tải dữ liệu phân trang.'
        );
    }

    public function with(array $relations)
    {
        return $this->model->with($relations);
    }

    public function allWith(array $relations = [])
    {
        return $this->safeExecute(
            fn() => $this->model->with($relations)->get(),
            'Không thể lấy dữ liệu với quan hệ.'
        );
    }

    public function paginateWith(array $relations = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->safeExecute(
            fn() => $this->model->with($relations)->paginate($perPage),
            'Không thể tải dữ liệu phân trang có quan hệ.'
        );
    }

    /**
     * Tạo bản ghi mới
     */
    public function create(array $data): Model
    {
        return $this->safeExecute(
            fn() => $this->model->create($data),
            'Không thể tạo bản ghi mới.'
        );
    }

    /**
     * Cập nhật bản ghi theo ID
     */
    public function update(int|string $id, array $data): bool
    {
        return $this->safeExecute(
            function () use ($id, $data) {
                $record = $this->find($id);
                if (! $record) {
                    throw new Exception("Không tìm thấy bản ghi để cập nhật (ID: {$id}).");
                }

                return $record->update($data);
            },
            'Không thể cập nhật bản ghi.'
        );
    }

    /**
     * Xóa bản ghi theo ID
     */
    public function delete(int|string $id): bool
    {
        return $this->safeExecute(
            function () use ($id) {
                $record = $this->find($id);
                if (! $record) {
                    throw new Exception("Không tìm thấy bản ghi để xóa (ID: {$id}).");
                }

                return (bool) $record->delete();
            },
            'Không thể xóa bản ghi.'
        );
    }
}
