<?php

namespace App\Repositories\Interfaces;
//use Illuminate\Interfaces\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface
{
    public function all();
    public function find(int|string $id);
    public function paginate(int $perPage = 15);
    public function create(array $data);
    public function update(int|string $id, array $data);
    public function delete(int|string $id);


    public function paginateWithSearch(?string $search = null, int $perPage = 10, $item = [] ,?string $startDate = null, ?string $endDate = null);

    public function getTotals(?string $search = null, $item = []): array;
}
