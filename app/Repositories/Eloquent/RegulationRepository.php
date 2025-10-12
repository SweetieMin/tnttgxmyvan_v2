<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\RegulationRepositoryInterface;
use App\Models\Regulation;
use App\Repositories\BaseRepository;

class RegulationRepository extends BaseRepository implements RegulationRepositoryInterface
{
    public function __construct(Regulation $model)
    {
        parent::__construct($model);
    }
}