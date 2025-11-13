<?php

namespace App\Livewire\Finance\Transaction;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Exports\TransactionExport;

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

    public $startDate;

    public $endDate;

    public $perPage = 10;

    public $totalIncome;

    public $totalExpense;

    public $totalDebt;

    public $balance;

    public function boot(TransactionRepositoryInterface $transactionRepository, TransactionItemRepositoryInterface $transactionItemRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->transactionItemRepository = $transactionItemRepository;
    }

    public function render()
    {
        $transactions = $this->transactionRepository
            ->paginateWithSearch(
                $this->search,
                $this->perPage,
                $this->itemFilter,
                $this->startDate,
                $this->endDate
            );

        $items = $this->transactionItemRepository
            ->all(['ordering' => 'asc']);

        $totals = $this->transactionRepository->getTotals($this->search, $this->itemFilter);

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
                $this->startDate,
                $this->endDate
            ),
            $fileName
        );
    }
}
