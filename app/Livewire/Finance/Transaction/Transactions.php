<?php

namespace App\Livewire\Finance\Transaction;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Exports\TransactionExport;
use Flux\DateRange;
use App\Services\TransactionService;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\TransactionItemRepositoryInterface;

#[Title('Tiền Quỹ')]
class Transactions extends Component
{
    use WithPagination;

    protected TransactionRepositoryInterface $transactionRepository;

    protected TransactionItemRepositoryInterface $transactionItemRepository;

    public $search;

    public $itemFilter = [];

    public $startDate = null;

    public $endDate = null;

    public $perPage = 10;

    public $totalIncome;

    public $totalExpense;

    public $totalDebt;

    public $balance;

    public $sortBy = 'status';

    public $sortDirection = 'desc';

    public ?DateRange $range = null;

    public $statusFilter = ['paid', 'pending'];

    public $status = [
        [
            'value' => 'paid',
            'name' => 'Đã chi',
        ],
        [
            'value' => 'pending',
            'name' => 'Chưa chi',
        ],
    ];



    public function boot(TransactionRepositoryInterface $transactionRepository, TransactionItemRepositoryInterface $transactionItemRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->transactionItemRepository = $transactionItemRepository;
    }

    public function mount() {
        $this->range = new DateRange();
    }

    public function sort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function render()
    {

        if ($this->range?->startDate && $this->range?->endDate) {
            $this->startDate = $this->range->startDate?->format('Y-m-d');
            $this->endDate   = $this->range->endDate?->format('Y-m-d');
        }else{
            $this->startDate = null;
            $this->endDate = null;
        }
        
        // Convert chuỗi rỗng thành null để tránh lỗi type
        $perPage = $this->perPage === '' || $this->perPage === null ? null : (int) $this->perPage;
        
        $transactions = $this->transactionRepository
            ->paginateWithSearch(
                $this->search,
                $perPage,
                $this->itemFilter,
                $this->statusFilter,
                $this->startDate,
                $this->endDate,
                $this->sortBy,
                $this->sortDirection
            );
            

        $items = $this->transactionItemRepository
            ->all(['ordering' => 'asc']);

        $totals = $this->transactionRepository->getTotals($this->search, $this->itemFilter, $this->statusFilter, $this->startDate, $this->endDate);

        $this->totalIncome  = $totals['income'];
        $this->totalExpense = $totals['expense'];
        $this->totalDebt    = $totals['debt'];
        $this->balance      = $totals['balance'];

        return view('livewire.finance.transaction.transactions', [
            'transactions' => $transactions,
            'items' => $items,
        ]);
    }

    public function addTransaction()
    {
        $this->dispatch('addTransaction');
    }

    public function editTransaction($id)
    {
        $this->dispatch('editTransaction', $id);
    }

    public function deleteTransaction($id)
    {
        $this->dispatch('deleteTransaction', $id);
    }

    public function exportData(TransactionService $transactionService)
    {
        $fileName = $transactionService->generateName($this->itemFilter);

        return Excel::download(
            new TransactionExport(
                $this->search,
                $this->itemFilter,
                $this->statusFilter,
                $this->startDate,
                $this->endDate
            ),
            $fileName
        );
    }
}
