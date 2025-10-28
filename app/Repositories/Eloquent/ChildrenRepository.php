<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\ChildrenRepositoryInterface;
use App\Models\User;
use App\Repositories\BaseRepository;

class ChildrenRepository extends BaseRepository implements ChildrenRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    
}
