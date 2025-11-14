<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\BaseRepository;
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

    public function paginateWithSearch(
        ?string $search = null,
        ?int $perPage = null,
        $item = [],
        $status = [],
        ?string $startDate = null,
        ?string $endDate = null,
        string $sortBy = 'status',
        string $sortDirection = 'desc'
    ) {
        $query = $this->model->with(['item']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%");
            });
        }

        $query->when($item, function ($q) use ($item) {
            $q->whereIn('transaction_item_id', (array) $item);
        });

        $query->when($status, function ($q) use ($status) {
            $q->whereIn('status', (array) $status);
        });

        if ($startDate && $endDate) {
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('transaction_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('transaction_date', '<=', $endDate);
        }

        // ⭐ Nếu sortBy được truyền, ưu tiên sort theo nó trước
        $query->orderBy($sortBy, $sortDirection);

        // ⭐ Sau đó mới sort thêm theo ngày cho ổn định
        if ($sortBy !== 'transaction_date') {
            $query->orderByDesc('transaction_date');
        }

        $query->orderByDesc('created_at');

        return $query->paginate($perPage);
    }


    public function getTotals(?string $search = null, $item = [], $status = [], ?string $startDate = null, ?string $endDate = null): array
    {
        $query = $this->model->query();

        // Nếu lọc theo hạng mục
        $query->when($item, function ($q) use ($item) {
            $q->whereIn('transaction_item_id', (array) $item);
        });

        $query->when($status, function ($q) use ($status) {
            $q->whereIn('status', (array) $status);
        });

        // Nếu lọc theo ngày
        if ($startDate && $endDate) {
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('transaction_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('transaction_date', '<=', $endDate);
        }

        // Nếu có search
        $query->when($search, fn($q) => $q->where('description', 'like', "%{$search}%"));

        // Tổng thu và chi
        $totalIncome = (clone $query)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $query)->where('type', 'expense')->where('status', 'paid')->sum('amount');
        $totalDebt = (clone $query)->where('type', 'expense')->where('status', 'pending')->sum('amount');

        return [
            'income'  => $totalIncome,
            'expense' => $totalExpense,
            'debt' => $totalDebt,
            'balance' => $totalIncome - $totalExpense,
        ];
    }
}
