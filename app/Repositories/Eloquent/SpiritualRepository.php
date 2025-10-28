<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\SpiritualRepositoryInterface;
use App\Models\User;
use App\Repositories\BaseRepository;

class SpiritualRepository extends BaseRepository implements SpiritualRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    
}
