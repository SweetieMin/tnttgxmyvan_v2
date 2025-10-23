<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Exception;
use App\Repositories\Traits\HasOrdering;

abstract class BaseRepository
{
    use HasOrdering;

    protected Model $model;

    /**
     * Nếu repo con cần scoped ordering, set $groupColumn = 'academic_year_id';
     * Nếu không, để null.
     */
    protected ?string $groupColumn = null;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

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

    public function all(array $orderBy = []): Collection
    {
        return $this->safeExecute(function () use ($orderBy) {
            $query = $this->model->newQuery();

            foreach ($orderBy as $col => $dir) {
                $query->orderBy($col, $dir);
            }

            if (empty($orderBy) && Schema::hasColumn($this->model->getTable(), 'ordering')) {
                $query->orderBy('ordering');
            }

            return $query->get();
        }, 'Không thể lấy danh sách bản ghi.');
    }

    public function find(int|string $id): ?Model
    {
        return $this->safeExecute(fn() => $this->model->find($id), 'Không thể tìm bản ghi có ID ' . $id);
    }

    public function paginate(int $perPage = 15, array $orderBy = []): LengthAwarePaginator
    {
        return $this->safeExecute(function () use ($perPage, $orderBy) {
            $query = $this->model->newQuery();

            foreach ($orderBy as $col => $dir) {
                $query->orderBy($col, $dir);
            }

            if (empty($orderBy) && Schema::hasColumn($this->model->getTable(), 'ordering')) {
                $query->orderBy('ordering');
            }

            return $query->paginate($perPage);
        }, 'Không thể tải dữ liệu phân trang.');
    }

    public function with(array $relations)
    {
        return $this->model->with($relations);
    }

    public function allWith(array $relations = [], array $orderBy = []): Collection
    {
        return $this->safeExecute(function () use ($relations, $orderBy) {
            $query = $this->model->with($relations);

            foreach ($orderBy as $col => $dir) {
                $query->orderBy($col, $dir);
            }

            if (empty($orderBy) && Schema::hasColumn($this->model->getTable(), 'ordering')) {
                $query->orderBy('ordering');
            }

            return $query->get();
        }, 'Không thể lấy dữ liệu với quan hệ.');
    }

    public function paginateWith(array $relations = [], int $perPage = 15, array $orderBy = []): LengthAwarePaginator
    {
        return $this->safeExecute(function () use ($relations, $perPage, $orderBy) {
            $query = $this->model->with($relations);

            foreach ($orderBy as $col => $dir) {
                $query->orderBy($col, $dir);
            }

            if (empty($orderBy) && Schema::hasColumn($this->model->getTable(), 'ordering')) {
                $query->orderBy('ordering');
            }

            return $query->paginate($perPage);
        }, 'Không thể tải dữ liệu phân trang có quan hệ.');
    }

    /**
     * Nếu repo con cần chuẩn hoá dữ liệu trước create/update, override method này.
     */
    protected function prepareData(array $data): array
    {
        return $data; // mặc định không làm gì
    }

    public function create(array $data): Model
    {
        return $this->safeExecute(function () use ($data) {
            $data = $this->prepareData($data);
            $data = $this->autoOrdering($data, $this->groupColumn); // 👈 tự gán ordering theo group nếu có

            return $this->model->create($data);
        }, 'Không thể tạo bản ghi mới.');
    }

    public function update(int|string $id, array $data): bool
    {
        return $this->safeExecute(function () use ($id, $data) {
            $data = $this->prepareData($data);

            $record = $this->find($id);
            if (! $record) {
                throw new Exception("Không tìm thấy bản ghi để cập nhật (ID: {$id}).");
            }

            return $record->update($data);
        }, 'Không thể cập nhật bản ghi.');
    }

    public function delete(int|string $id): bool
    {
        return $this->safeExecute(function () use ($id) {
            $record = $this->find($id);
            if (! $record) {
                throw new Exception("Không tìm thấy bản ghi để xóa (ID: {$id}).");
            }

            $deleted = (bool) $record->delete();

            // Sau khi xoá: reorder theo group nếu có
            if ($deleted && Schema::hasColumn($this->model->getTable(), 'ordering')) {
                $this->reorder($this->groupColumn);
            }

            return $deleted;
        }, 'Không thể xóa bản ghi.');
    }
}
