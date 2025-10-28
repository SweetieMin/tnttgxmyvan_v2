<?php

namespace App\Livewire\Finance\Transaction;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Livewire\Attributes\Title;

#[Title('Tiền Quỹ')]
class Transactions extends Component
{
    use WithPagination;

    protected TransactionRepositoryInterface $transactionRepository;


    public function boot(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function render()
    {
        $transactions = $this->transactionRepository
            ->paginate(15);

        return view('livewire.finance.transaction.transactions', [
            'transactions' => $transactions,
        ]);
    }

    public function addTransaction()
    {
        $this->dispatch('addTransaction');
    }

    public function editTransaction($id){
        $this->dispatch('editTransaction', $id);
    }

    public function deleteTransaction($id){
        $this->dispatch('deleteTransaction', $id);
    }
}
