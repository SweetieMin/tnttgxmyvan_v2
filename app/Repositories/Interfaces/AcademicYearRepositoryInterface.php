<?php

namespace App\Repositories\Interfaces;

interface AcademicYearRepositoryInterface
{
    public function all();
    public function find(int|string $id);
    public function create(array $data);
    public function update(int|string $id, array $data);
    public function delete(int|string $id);
}
