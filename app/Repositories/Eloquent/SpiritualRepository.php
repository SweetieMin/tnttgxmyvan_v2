<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\SpiritualRepositoryInterface;

class SpiritualRepository extends BaseRepository implements SpiritualRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getSpiritualWithSearchAndPage(?string $search = null, ?int $perPage = 15): LengthAwarePaginator
    {
        return $this->safeExecute(function () use ($search, $perPage) {

            $query = $this->model->newQuery()
                ->with('roles')  // load luôn danh sách role
                ->whereHas(
                    'roles',
                    fn($q) =>
                    $q->where('type', 'spiritual')   // 🔥 chỉ lấy người có role spiritual
                );

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('christian_name', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('account_code', 'like', "%{$search}%");
                });
            }

            return $query
                ->orderByRole()
                ->orderBy('name')
                ->paginate($perPage);
        }, 'Không thể tải dữ liệu phân trang.');
    }

    public function findSpiritualWithRelations(int|string $id)
    {
        return $this->safeExecute(function () use ($id) {
            return $this->model->with('roles')->find($id);
        }, 'Không thể tìm bản ghi có ID ' . $id);
    }
}
