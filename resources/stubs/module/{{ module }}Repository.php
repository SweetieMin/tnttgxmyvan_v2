<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\{{ module }}RepositoryInterface;
use App\Models\{{ module }};
use App\Repositories\BaseRepository;

class {{ module }}Repository extends BaseRepository implements {{ module }}RepositoryInterface
{
    public function __construct({{ module }} $model)
    {
        parent::__construct($model);
    }

    
}
