<?php

namespace App\Livewire\Finance\TransactionItem;

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Validation\Finance\TransactionItemRules;
use App\Traits\Finance\HandlesTransactionItemForm;
use App\Repositories\Interfaces\TransactionItemRepositoryInterface;


class ActionsTransactionItem extends Component
{
    use HandlesTransactionItemForm;

    protected TransactionItemRepositoryInterface $transaction_itemRepository;

    public $isEditTransactionItemMode = false;

    public $name;

    public $is_system = 0;

    public $description;

    public $transaction_itemID = null;

    public function resetForm()
    {
        $this->reset([
            'name',
            'transaction_itemID',
            'description',
        ]);

        $this->isEditTransactionItemMode = false;
        $this->is_system = false;

        $this->resetErrorBag();
    }

    /**
     * Quy tắc xác thực
     */
    protected function rules()
    {
        return TransactionItemRules::rules($this->transaction_itemID);
    }

    /**
     * Thông báo lỗi xác thực (tiếng Việt)
     */
    protected function messages()
    {
        return TransactionItemRules::messages();
    }

    public function boot(TransactionItemRepositoryInterface $transaction_itemRepository)
    {
        $this->transaction_itemRepository = $transaction_itemRepository;
    }


    public function render()
    {
        return view('livewire.finance.transaction-item.actions-transaction-item');
    }

    #[On('addTransactionItem')]
    public function addTransactionItem()
    {
        $this->resetForm();
        Flux::modal('action-transaction-item')->show();
    }

    public function createTransactionItem()
    {
        $this->validate();

        $data = $this->only([
            'name',
            'description',
            'is_system',
        ]);

        try {
            $this->transaction_itemRepository->create($data);

            session()->flash('success', 'Hạng mục thu chi tạo thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo transaction_item thất bại.' . $e->getMessage());
        }

        $this->redirectRoute('admin.finance.transaction-items', navigate: true);
    }

    #[On('editTransactionItem')]
    public function editTransactionItem($id)
    {
        $this->resetForm();

        $transaction_item = $this->transaction_itemRepository->find($id);

        if ($transaction_item) {
            // Gán dữ liệu vào form
            $this->transaction_itemID = $transaction_item->id;
            $this->isEditTransactionItemMode = true;

            $this->name = $transaction_item->name;
            $this->description = $transaction_item->description;
            $this->is_system = $transaction_item->is_system;


            // Hiển thị modal
            Flux::modal('action-transaction-item')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy transaction_item');
            return $this->redirectRoute('admin.finance.transaction-items', navigate: true);
        }
    }

    public function updateTransactionItem()
    {

        $this->validate();

        $data = $this->only([
            'name',
            'description',
            'is_system',
        ]);

        try {
            $this->transaction_itemRepository->update($this->transaction_itemID, $data);

            session()->flash('success', 'Hạng mục thu chi cập nhật thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật transaction_item thất bại.' . $e->getMessage());
        }

        $this->redirectRoute('admin.finance.transaction-items', navigate: true);
    }

    #[On('deleteTransactionItem')]
    public function deleteTransactionItem($id)
    {

        $this->resetForm();

        $transaction_item = $this->transaction_itemRepository->find($id);

        if ($transaction_item) {
            // Gán dữ liệu vào form
            $this->transaction_itemID = $transaction_item->id;

            // Hiển thị modal
            Flux::modal('delete-transaction-item')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy transaction_item');
            return $this->redirectRoute('admin.finance.transaction-items', navigate: true);
        }
    }

    public function deleteTransactionItemConfirm()
    {
        try {
            $this->transaction_itemRepository->delete($this->transaction_itemID);

            session()->flash('success', 'Hạng mục thu chi xoá thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Xoá transaction_item thất bại.' . $e->getMessage());
        }

        $this->redirectRoute('admin.finance.transaction-items', navigate: true);
    }
}
