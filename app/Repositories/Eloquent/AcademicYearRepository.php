<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\AcademicYearRepositoryInterface;
use App\Models\AcademicYear;
use App\Repositories\BaseRepository;

class AcademicYearRepository extends BaseRepository implements AcademicYearRepositoryInterface
{
    public function __construct(AcademicYear $model)
    {
        parent::__construct($model);
    }

    public function getAcademicOngoingAndUpcoming(){
        return $this->model
            ->whereIn('status_academic', ['upcoming', 'ongoing'])->get();
    }

    public function getAcademicOngoingAndFinished()
    {
        return $this->model
            ->whereIn('status_academic', ['finished', 'ongoing'])->get();
    }

    public function getAcademicOngoingNow()
    {
        // Lấy thời gian hiện tại
        $now = now();

        // Ưu tiên: tìm theo khoảng ngày
        $academic = $this->model
            ->where('catechism_start_date', '<=', $now)
            ->where('catechism_end_date', '>=', $now)
            ->first();

        // Nếu không tìm thấy → fallback về status = 'ongoing'
        if (! $academic) {
            $academic = $this->model
                ->where('status_academic', 'ongoing')
                ->first();
        }

        return $academic;
    }


    public function paginateWithSearch(?string $search = null, ?int $perPage = null)
    {
        $query = $this->model->query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        return $query->orderByDesc('id')->paginate($perPage);
    }
}
