<?php

namespace App\Repositories\Traits;

/**
 * 🔄 Trait: HandlesSorting
 * 
 * Trait này xử lý logic sắp xếp (orderBy) chung cho repository.
 * Dùng để áp dụng thứ tự sắp xếp linh hoạt khi lấy dữ liệu.
 * 
 * ✅ Tính năng chính:
 * - Cho phép orderBy động từ tham số mảng
 * - Nếu không truyền orderBy → tự động sắp xếp theo cột `ordering` (nếu có)
 *
 * 👉 Mục tiêu: Giúp code all(), paginate(), allWith() gọn và thống nhất hơn.
 */
trait HandlesSorting
{
    /**
     * Áp dụng sắp xếp cho query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $orderBy  Mảng ['column' => 'ASC|DESC']
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applySorting($query, array $orderBy)
    {
        // Nếu có orderBy → áp dụng tuần tự
        if (!empty($orderBy)) {
            foreach ($orderBy as $column => $direction) {
                $query->orderBy($column, $direction);
            }
        }
        // Nếu không có orderBy → sắp xếp theo ordering (nếu model có cột đó)
        elseif (method_exists($this, 'hasOrderingColumn') && $this->hasOrderingColumn()) {
            $query->orderBy('ordering');
        }

        return $query;
    }
}
