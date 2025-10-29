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

            $data['amount'] = (int) str_replace([',', '.', ' '], '', $data['amount']);
            return $this->model->create($data);
        }, 'Không thể tạo bản ghi mới.');
    }

    public function paginateWithSearch(?string $search = null, int $perPage = 10, $item = null, ?string $startDate = null, ?string $endDate = null)

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

        // 📅 Lọc theo ngày bắt đầu / kết thúc
        if ($startDate && $endDate) {
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('transaction_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('transaction_date', '<=', $endDate);
        }

        // 🔽 Sắp xếp mới nhất trước
        return $query
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getTotals(?string $search = null, $item = null): array
    {
        $query = $this->model->query();

        // Nếu lọc theo hạng mục
        $query->when($item, fn($q) => $q->where('transaction_item_id', $item));

        // Nếu có search
        $query->when($search, fn($q) => $q->where('description', 'like', "%{$search}%"));

        // Tổng thu và chi
        $totalIncome = (clone $query)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $query)->where('type', 'expense')->sum('amount');

        return [
            'income'  => $totalIncome,
            'expense' => $totalExpense,
            'balance' => $totalIncome - $totalExpense,
        ];
    }
}
