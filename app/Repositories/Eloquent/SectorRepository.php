<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\SectorRepositoryInterface;
use App\Models\Sector;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SectorRepository extends BaseRepository implements SectorRepositoryInterface
{
    /**
     * SectorRepository với ordering đơn giản
     * 
     * ✅ Tính năng:
     * - Hỗ trợ drag-drop sortable
     * - Search theo tên ngành
     * - Ordering đơn giản không group
     */
    
    public function __construct(Sector $model)
    {
        parent::__construct($model);
    }

    /**
     * Lấy danh sách sector với search
     */
    public function sectorWithSearch(?string $search = null): LengthAwarePaginator
    {
        return $this->safeExecute(function () use ($search) {
            $query = $this->model->with(['academicYear', 'program']);

            // Search theo tên sector
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('sector', 'like', "%{$search}%");
                });
            }

            return $query->orderBy('ordering')->paginate(15);
        }, 'Lỗi khi tìm kiếm ngành sinh hoạt');
    }

    /**
     * Lấy danh sách sector với search và filter theo năm học
     */
    public function sectorWithSearchAndYear(?string $search = null, ?int $year = null): LengthAwarePaginator
    {
        return $this->safeExecute(function () use ($search, $year) {
            $query = $this->model->with(['academicYear', 'program']);

            // Filter theo năm học
            if ($year) {
                $query->where('academic_year_id', $year);
            }

            // Search theo tên sector
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('sector', 'like', "%{$search}%");
                });
            }

             // Sắp xếp theo ordering trong từng năm học
             return $query->orderBy('academic_year_id')
             ->orderBy('ordering')
             ->paginate(15);
        }, 'Lỗi khi tìm kiếm ngành sinh hoạt');
    }

    /**
     * Override updateOrdering để không cần group
     */
    public function updateOrdering(array $orderedIds, ?string $groupColumn = null, $groupValue = null): bool
    {
        return $this->safeExecute(function () use ($orderedIds) {
            foreach ($orderedIds as $index => $id) {
                $this->model->where('id', $id)->update(['ordering' => $index + 1]);
            }
            return true;
        }, 'Lỗi khi cập nhật thứ tự ngành sinh hoạt');
    }
}
