<?php

namespace App\Livewire\Finance\Transaction;

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

use App\Validation\Finance\TransactionRules;
use App\Traits\Finance\HandlesTransactionForm;
use App\Repositories\Interfaces\TransactionRepositoryInterface;


class ActionsTransaction extends Component
{
    use HandlesTransactionForm;
    use WithFileUploads;

    protected TransactionRepositoryInterface $transactionRepository;

    public $isEditTransactionMode = false;

    public $files;

    public $transactionID;

    /**
     * Quy tắc xác thực
     */
    protected function rules()
    {
        return TransactionRules::rules($this->transactionID);
    }

    /**
     * Thông báo lỗi xác thực (tiếng Việt)
     */
    protected function messages()
    {
        return TransactionRules::messages();
    }

    public function boot(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }


    public function removeFile($index)
    {
        unset($this->files[$index]);
        $this->files = array_values($this->files);
    }


    public function render()
    {
        return view('livewire.finance.transaction.actions-transaction');
    }

    #[On('addTransaction')]
    public function addTransaction()
    {
        $this->resetForm();
        Flux::modal('action-transaction')->show();
    }

    public function createTransaction()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->transactionRepository->create($data);

            session()->flash('success', 'Transaction tạo thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo transaction thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.transactions', navigate: true);
    }

    #[On('editTransaction')]
    public function editTransaction($id)
    {
        $this->resetForm();

        $transaction = $this->transactionRepository->find($id);

        if ($transaction) {
            // Gán dữ liệu vào form
            $this->transactionID = $transaction->id;
            $this->isEditTransactionMode = true;
    
            

            // Hiển thị modal
            Flux::modal('action-transaction')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy transaction');
            return $this->redirectRoute('admin.management.transactions', navigate: true);
        }

    }

    public function updateTransaction()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->transactionRepository->update($this->transactionID,$data);

            session()->flash('success', 'Transaction cập nhật thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật transaction thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.transactions', navigate: true);
    }

    #[On('deleteTransaction')]
    public function deleteTransaction($id)
    {

        $this->resetForm();

        $transaction = $this->transactionRepository->find($id);

        if ($transaction) {
            // Gán dữ liệu vào form
            $this->transactionID = $transaction->id;
                
            // Hiển thị modal
            Flux::modal('delete-transaction')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy transaction');
            return $this->redirectRoute('admin.management.transactions', navigate: true);
        }

    }

    public function deleteTransactionConfirm()
    {
        try {
            $this->transactionRepository->delete($this->transactionID);

            session()->flash('success', 'Transaction xoá thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Xoá transaction thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.transactions', navigate: true);
    }
}
