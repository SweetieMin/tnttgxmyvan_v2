<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\SectorRepositoryInterface;
use App\Models\Sector;
use App\Repositories\BaseRepository;

class SectorRepository extends BaseRepository implements SectorRepositoryInterface
{
    public function __construct(Sector $model)
    {
        parent::__construct($model);
    }
}