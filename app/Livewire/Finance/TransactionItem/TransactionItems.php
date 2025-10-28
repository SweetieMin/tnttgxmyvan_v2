<?php

namespace App\Livewire\Finance\TransactionItem;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\TransactionItemRepositoryInterface;
use Livewire\Attributes\Title;

#[Title('Hạng mục chi')]
class TransactionItems extends Component
{
    use WithPagination;

    protected TransactionItemRepositoryInterface $transaction_itemRepository;


    public function boot(TransactionItemRepositoryInterface $transaction_itemRepository)
    {
        $this->transaction_itemRepository = $transaction_itemRepository;
    }

    public function render()
    {
        $transaction_items = $this->transaction_itemRepository
            ->paginate(15);

        return view('livewire.finance.transaction-item.transaction-items', [
            'transaction_items' => $transaction_items,
        ]);
    }

    public function addTransactionItem()
    {
        $this->dispatch('addTransactionItem');
    }

    public function editTransactionItem($id){
        $this->dispatch('editTransactionItem', $id);
    }

    public function deleteTransactionItem($id){
        $this->dispatch('deleteTransactionItem', $id);
    }

    public function updateTransactionItemsOrdering($ids)
    {
        $success = $this->transaction_itemRepository->updateOrdering($ids);

        if ($success) {
            session()->flash('success', 'Sắp xếp chương trình học thành công!');
        } else {
            session()->flash('error', 'Sắp xếp thất bại! Vui lòng thử lại.');
        }

        $this->redirectRoute('admin.finance.transaction-items', navigate: true);
    }
}
