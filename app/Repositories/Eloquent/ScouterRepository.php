<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\ScouterRepositoryInterface;
use App\Models\User;
use App\Repositories\BaseRepository;

class ScouterRepository extends BaseRepository implements ScouterRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    
}
