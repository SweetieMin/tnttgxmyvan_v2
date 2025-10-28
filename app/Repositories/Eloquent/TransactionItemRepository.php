<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\TransactionItemRepositoryInterface;
use App\Models\TransactionItem;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransactionItemRepository extends BaseRepository implements TransactionItemRepositoryInterface
{
    public function __construct(TransactionItem $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15, array $orderBy = []): LengthAwarePaginator
    {
        return $this->safeExecute(function () use ($perPage, $orderBy) {
            $query = $this->model->newQuery();

            foreach ($orderBy as $col => $dir) {
                $query->orderBy($col, $dir);
            }


            return $query->orderBy('ordering')->paginate($perPage);
        }, 'Không thể tải dữ liệu phân trang.');
    }
    
}
