<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Models\Role;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function getRoleExceptCurrentRole(?int $id = null)
    {
        return $this->model->with('subRoles')
        ->when($id, fn($query) => $query->where('id', '!=', $id))
        ->orderBy('ordering')
        ->get();
    }

    public function roleWithSearchAndPage(?string $search = null, ?int $perPage = null) :LengthAwarePaginator
    {
        return $this->safeExecute(function () use ($perPage, $search) {
            $query = $this->model->newQuery();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            return $query->paginate($perPage);
        }, 'Không thể tải dữ liệu phân trang.');
    }

    
}
