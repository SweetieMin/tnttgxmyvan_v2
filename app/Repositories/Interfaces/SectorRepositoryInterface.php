<?php

namespace App\Repositories\Interfaces;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SectorRepositoryInterface
{
    public function all();
    public function find(int|string $id);
    public function paginate(int $perPage = 15);
    public function create(array $data);
    public function update(int|string $id, array $data);
    public function delete(int|string $id);

    public function sectorWithSearch(?string $search = null);
    public function sectorWithSearchAndYear(?string $search = null, ?int $year = null): LengthAwarePaginator;
    public function updateOrdering(array $orderedIds, ?string $groupColumn = null, $groupValue = null): bool;
}
