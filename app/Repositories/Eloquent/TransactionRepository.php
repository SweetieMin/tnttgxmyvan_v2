<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Models\Transaction;
use App\Repositories\BaseRepository;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }
}