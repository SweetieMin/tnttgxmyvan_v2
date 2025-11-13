<?php

namespace App\Livewire\Finance\TransactionItem;

use Flux\Flux;
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
        try {
            $success = $this->transaction_itemRepository->updateOrdering($ids);

            if ($success) {
                Flux::toast(
                    heading: 'Thành công',
                    text: 'Thứ tự hạng mục chi đã được cập nhật.',
                    variant: 'success',
                );
            } else {
                Flux::toast(
                    heading: 'Đã xảy ra lỗi!',
                    text: 'Không thể cập nhật thứ tự hạng mục chi.',
                    variant: 'error',
                );
            }
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Đã xảy ra lỗi!',
                text: 'Lỗi khi cập nhật thứ tự: ' . (app()->environment('local') ? $e->getMessage() : 'Vui lòng thử lại sau.'),
                variant: 'error',
            );
        }

        $this->redirectRoute('admin.finance.transaction-items', navigate: true);
    }
}
