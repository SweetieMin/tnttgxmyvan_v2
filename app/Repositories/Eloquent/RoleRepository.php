<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Models\Role;
use App\Repositories\BaseRepository;

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

    
}
