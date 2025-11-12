<?php

namespace App\Repositories\Eloquent;

use App\Models\Regulation;
use App\Repositories\BaseRepository;
use App\Repositories\Traits\HasSortableOrdering;
use App\Repositories\Interfaces\RegulationRepositoryInterface;

class RegulationRepository extends BaseRepository implements RegulationRepositoryInterface
{
    protected ?string $groupColumn = 'academic_year_id';

    use HasSortableOrdering;

    public function __construct(Regulation $model)
    {
        parent::__construct($model);
    }

    public function updateRegulationOrdering(array $orderedIds, int $academicYearId): bool
    {
        return $this->updateSortableOrdering($orderedIds, $academicYearId);
    }

    public function regulationWithSearchAndYear(?string $search = null, ?int $year = null)
    {
        return $this->safeExecute(function () use ($search, $year) {
            $query = $this->model->with(['academicYear']);

            // Filter theo năm học
            if ($year) {
                $query->where('academic_year_id', $year);
            }

            // Search theo tên course
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                        ->orWhere('points','like', "%{$search}%");
                });
            }

            // Sắp xếp theo ordering trong từng năm học
            return $query->orderBy('academic_year_id')
                
                ->orderBy('ordering')
                ->orderByRaw("type = 'minus'")
                ->get();
        }, 'Không thể lấy danh sách course với tìm kiếm và lọc.');
    }
}
