<?php

namespace App\Repositories\Interfaces;
//use Illuminate\Interfaces\Pagination\LengthAwarePaginator;

interface CourseRepositoryInterface
{
    public function all();
    public function find(int|string $id);
    public function paginate(int $perPage = 15);
    public function create(array $data);
    public function update(int|string $id, array $data);
    public function delete(int|string $id);


    public function courseWithSearchAndYear(?string $search = null, ?int $year = 1);
}
