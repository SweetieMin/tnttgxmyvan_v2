<?php

namespace App\Repositories\Interfaces;
//use Illuminate\Interfaces\Pagination\LengthAwarePaginator;

interface AcademicYearRepositoryInterface
{
    public function all();
    public function find(int|string $id);
    public function paginate(int $perPage = 15);
    public function create(array $data);
    public function update(int|string $id, array $data);
    public function delete(int|string $id);

    public function getAcademicOngoingAndUpcoming();
    public function getAcademicOngoingAndFinished();
    public function getAcademicOngoingNow();

    public function paginateWithSearch(?string $search = null, ?int $perPage = null);
}