<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Interfaces\TransactionRepositoryInterface;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function create(array $data): Model
    {
        return $this->safeExecute(function () use ($data) {
            $data['created_by'] = $data['created_by'] ?? Auth::id();

            $data['amount'] = (int) str_replace([',', '.', ' '], '', $data['amount']);
            return $this->model->create($data);
        }, 'Không thể tạo bản ghi mới.');
    }

    public function paginateWithSearch(?string $search = null, int $perPage = 10, $item = null)

    {
        $query = $this->model->query();

        // 🔍 Nếu có từ khóa tìm kiếm (mô tả)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%");
            });
        }

        // 🎯 Nếu chọn 1 item cụ thể (ví dụ transaction_item_id = 3)
        $query->when($item, function ($q) use ($item) {
            $q->where('transaction_item_id', $item);
        });

        // 🔽 Sắp xếp mới nhất trước
        return $query->orderByDesc('transaction_date')->paginate($perPage);
    }
}
