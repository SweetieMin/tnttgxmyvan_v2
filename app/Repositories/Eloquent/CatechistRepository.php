<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\CatechistRepositoryInterface;
use App\Models\User;
use App\Repositories\BaseRepository;

class CatechistRepository extends BaseRepository implements CatechistRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    
}
