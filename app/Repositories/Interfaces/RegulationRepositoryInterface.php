<?php

namespace App\Repositories\Interfaces;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RegulationRepositoryInterface
{
    public function all();
    public function find(int|string $id);
    public function paginate(int $perPage = 15);
    public function create(array $data);
    public function update(int|string $id, array $data);
    public function delete(int|string $id);

    public function regulationWithSearchAndYear(?string $search = null, ?int $year = null);

    public function updateRegulationOrdering(array $orderedIds, int $academicYearId);

}
