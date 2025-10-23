<?php

namespace App\Repositories\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

/**
 * 📊 Trait: HasOrdering
 * 
 * Trait này cung cấp cơ chế quản lý thứ tự (ordering) cho các model có cột `ordering`.
 * Dùng trong các repository khi muốn tự động gán, cập nhật hoặc sắp xếp lại thứ tự dữ liệu.
 * 
 * ✅ Tính năng chính:
 * - Kiểm tra model có cột `ordering`
 * - Tự động gán thứ tự mới khi create
 * - Cập nhật lại toàn bộ thứ tự sau khi xóa
 * - Hỗ trợ sắp xếp kéo-thả (Sortable)
 *
 * 👉 Giúp đồng bộ thứ tự hiển thị giữa frontend và database.
 */
trait HasOrdering
{
    protected function hasOrderingColumn(): bool
    {
        return Schema::hasColumn($this->model->getTable(), 'ordering');
    }

    /**
     * Tự gán ordering mới. Nếu có $groupColumn, chỉ tính trong nhóm đó.
     */
    protected function autoOrdering(array $data, ?string $groupColumn = null): array
    {
        if (! $this->hasOrderingColumn() || isset($data['ordering'])) {
            return $data;
        }

        $query = $this->model->newQuery();

        if ($groupColumn && isset($data[$groupColumn])) {
            $query->where($groupColumn, $data[$groupColumn]);
        }

        $maxOrdering = $query->max('ordering') ?? 0;
        $data['ordering'] = $maxOrdering + 1;

        return $data;
    }

    /**
     * Reorder: 1→n toàn bảng hoặc theo group.
     */
    protected function reorder(?string $groupColumn = null): void
    {
        if (! $this->hasOrderingColumn()) return;

        if ($groupColumn && Schema::hasColumn($this->model->getTable(), $groupColumn)) {
            $groups = $this->model->select($groupColumn)->distinct()->pluck($groupColumn);

            foreach ($groups as $groupValue) {
                $records = $this->model->where($groupColumn, $groupValue)
                    ->orderBy('ordering')
                    ->get();

                foreach ($records as $index => $record) {
                    $record->update(['ordering' => $index + 1]);
                }
            }
        } else {
            $records = $this->model->orderBy('ordering')->get();

            foreach ($records as $index => $record) {
                $record->update(['ordering' => $index + 1]);
            }
        }
    }

    /**
     * Update ordering từ mảng ID sau khi drag-drop.
     * Có thể truyền group để đảm bảo đúng phạm vi.
     */
    public function updateOrdering(array $orderedIds, ?string $groupColumn = null, $groupValue = null): bool
    {
        if (! $this->hasOrderingColumn()) return false;

        try {
            foreach ($orderedIds as $index => $id) {
                $query = $this->model->where('id', $id);

                if ($groupColumn && $groupValue !== null) {
                    $query->where($groupColumn, $groupValue);
                }

                $updated = $query->update(['ordering' => $index + 1]);
                if (! $updated) {
                    throw new \Exception("Không thể cập nhật bản ghi ID: {$id}");
                }
            }
            return true;
        } catch (\Throwable $e) {
            Log::error("Lỗi khi cập nhật ordering: " . $e->getMessage(), [
                'repository' => static::class,
                'model' => get_class($this->model),
                'groupColumn' => $groupColumn,
                'groupValue' => $groupValue,
                'ids' => $orderedIds,
            ]);
            return false;
        }
    }
}
