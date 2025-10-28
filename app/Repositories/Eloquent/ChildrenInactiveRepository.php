<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\ChildrenInactiveRepositoryInterface;
use App\Models\User;
use App\Repositories\BaseRepository;

class ChildrenInactiveRepository extends BaseRepository implements ChildrenInactiveRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    
}
