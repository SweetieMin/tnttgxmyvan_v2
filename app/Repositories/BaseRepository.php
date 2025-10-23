<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
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
     * Lấy tất cả bản ghi với tùy chọn sắp xếp
     */
    public function all(array $orderBy = []): Collection
    {
        return $this->safeExecute(
            function () use ($orderBy) {
                $query = $this->model->newQuery();

                // Nếu có orderBy thì áp dụng
                foreach ($orderBy as $column => $direction) {
                    $query->orderBy($column, $direction);
                }

                // Nếu có cột ordering mà chưa orderBy theo thì mặc định sắp xếp theo ordering
                if (empty($orderBy) && $this->hasOrderingColumn()) {
                    $query->orderBy('ordering');
                }

                return $query->get();
            },
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
     * Phân trang với tùy chọn sắp xếp
     */
    public function paginate(int $perPage = 15, array $orderBy = []): LengthAwarePaginator
    {
        return $this->safeExecute(
            function () use ($perPage, $orderBy) {
                $query = $this->model->newQuery();

                foreach ($orderBy as $column => $direction) {
                    $query->orderBy($column, $direction);
                }

                if (empty($orderBy) && $this->hasOrderingColumn()) {
                    $query->orderBy('ordering');
                }

                return $query->paginate($perPage);
            },
            'Không thể tải dữ liệu phân trang.'
        );
    }

    /**
     * Quan hệ
     */
    public function with(array $relations)
    {
        return $this->model->with($relations);
    }

    /**
     * Lấy tất cả bản ghi có quan hệ với tùy chọn sắp xếp
     */
    public function allWith(array $relations = [], array $orderBy = []): Collection
    {
        return $this->safeExecute(
            function () use ($relations, $orderBy) {
                $query = $this->model->with($relations);

                foreach ($orderBy as $column => $direction) {
                    $query->orderBy($column, $direction);
                }

                if (empty($orderBy) && $this->hasOrderingColumn()) {
                    $query->orderBy('ordering');
                }

                return $query->get();
            },
            'Không thể lấy dữ liệu với quan hệ.'
        );
    }

    /**
     * Phân trang có quan hệ và sắp xếp
     */
    public function paginateWith(array $relations = [], int $perPage = 15, array $orderBy = []): LengthAwarePaginator
    {
        return $this->safeExecute(
            function () use ($relations, $perPage, $orderBy) {
                $query = $this->model->with($relations);

                foreach ($orderBy as $column => $direction) {
                    $query->orderBy($column, $direction);
                }

                if (empty($orderBy) && $this->hasOrderingColumn()) {
                    $query->orderBy('ordering');
                }

                return $query->paginate($perPage);
            },
            'Không thể tải dữ liệu phân trang có quan hệ.'
        );
    }

    /**
     * Kiểm tra model có cột ordering không
     */
    protected function hasOrderingColumn(): bool
    {
        return Schema::hasColumn($this->model->getTable(), 'ordering');
    }

    protected function prepareDate(array &$data): void
    {
        if (method_exists($this, 'prepareDate')) {
            $this->prepareDate($data);
        }
    }

    /**
     * Tạo bản ghi mới — nếu có cột ordering thì tự động gán thứ tự
     */
    public function create(array $data): Model
    {
        return $this->safeExecute(
            function () use ($data) {

                $this->prepareDate($data);

                if ($this->hasOrderingColumn() && !isset($data['ordering'])) {
                    $maxOrdering = $this->model->max('ordering') ?? 0;
                    $data['ordering'] = $maxOrdering + 1;
                }

                return $this->model->create($data);
            },
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

                $this->prepareDate($data);

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
     * Xóa bản ghi theo ID — nếu có cột ordering thì cập nhật lại thứ tự
     */
    public function delete(int|string $id): bool
    {
        return $this->safeExecute(
            function () use ($id) {
                $record = $this->find($id);
                if (! $record) {
                    throw new Exception("Không tìm thấy bản ghi để xóa (ID: {$id}).");
                }

                $deleted = (bool) $record->delete();

                if ($deleted && $this->hasOrderingColumn()) {
                    $this->reorder();
                }

                return $deleted;
            },
            'Không thể xóa bản ghi.'
        );
    }

    /**
     * Cập nhật lại giá trị ordering từ 1 → n
     */
    protected function reorder(): void
    {
        $orderedRecords = $this->model->orderBy('ordering')->get();

        foreach ($orderedRecords as $index => $record) {
            $record->update(['ordering' => $index + 1]);
        }
    }

    /**
     * Sắp xếp vị trí ordering
     */
    /**
     * Cập nhật lại thứ tự ordering cho model hiện tại (nếu có cột 'ordering')
     */
    public function updateOrdering(array $orderedIds): void
    {
        // 🔹 Kiểm tra xem model có cột 'ordering' không
        if (! $this->model->getConnection()
            ->getSchemaBuilder()
            ->hasColumn($this->model->getTable(), 'ordering')) {
            return; // Nếu không có cột 'ordering' thì bỏ qua
        }

        // 🔹 Cập nhật theo thứ tự mới
        foreach ($orderedIds as $index => $id) {
            $this->model->where('id', $id)->update(['ordering' => $index + 1]);
        }
    }
}
