<?php

namespace App\Repositories\Interfaces;
//use Illuminate\Interfaces\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SpiritualRepositoryInterface
{
    public function all();
    public function find(int|string $id);
    public function paginate(int $perPage = 15);
    public function create(array $data);
    public function update(int|string $id, array $data);
    public function delete(int|string $id);

    public function getSpiritualWithSearchAndPage(?string $search = null, ?int $perPage = null) :LengthAwarePaginator;

    public function findSpiritualWithRelations(int|string $id);

}
