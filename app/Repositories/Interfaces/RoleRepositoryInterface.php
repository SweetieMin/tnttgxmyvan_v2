<?php

namespace App\Repositories\Interfaces;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RoleRepositoryInterface
{
    public function all();
    public function find(int|string $id);
    public function paginate(int $perPage = 15);
    public function create(array $data);
    public function update(int|string $id, array $data);
    public function delete(int|string $id);

    public function updateOrdering(array $orderedIds): bool;

    public function getRoleExceptCurrentRole(?int $id = null );

    public function roleWithSearchAndPage(?string $search = null, ?int $perPage = null) :LengthAwarePaginator;

    public function getRoleSpiritual();

}
