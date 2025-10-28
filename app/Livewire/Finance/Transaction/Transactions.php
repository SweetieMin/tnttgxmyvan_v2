<?php

namespace App\Livewire\Finance\Transaction;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\TransactionItemRepositoryInterface;

#[Title('Tiền Quỹ')]
class Transactions extends Component
{
    use WithPagination;

    protected TransactionRepositoryInterface $transactionRepository;

    protected TransactionItemRepositoryInterface $transactionItemRepository;

    public $search;

    public $itemFilter = null;

    public $perPage = 10;

    public $totalIncome;

    public $totalExpense;

    public $balance;

    public function boot(TransactionRepositoryInterface $transactionRepository, TransactionItemRepositoryInterface $transactionItemRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->transactionItemRepository = $transactionItemRepository;
    }

    public function render()
    {
        $transactions = $this->transactionRepository
            ->paginateWithSearch($this->search, $this->perPage, $this->itemFilter);

        $items = $this->transactionItemRepository
            ->all();

        $totals = $this->transactionRepository->getTotals($this->search, $this->itemFilter);

        $this->totalIncome  = $totals['income'];
        $this->totalExpense = $totals['expense'];
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
}
