<?php

namespace App\Livewire\Finance\Transaction;

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

use App\Services\TransactionService;
use App\Validation\Finance\TransactionRules;
use App\Traits\Finance\HandlesTransactionForm;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\TransactionItemRepositoryInterface;


class ActionsTransaction extends Component
{
    use HandlesTransactionForm;
    use WithFileUploads;

    protected TransactionService $fileService;

    protected TransactionRepositoryInterface $transactionRepository;

    protected TransactionItemRepositoryInterface $transactionItemRepository;

    public $isEditTransactionMode = false;

    public $transaction_item_id = 1;

    public $description;

    public $type = 'expense';

    public $amount;

    public $status = 'paid';

    public $in_charge;

    public $file;

    public $transaction_date;

    public $transactionID;

    public $existingFile = null;

    public function resetForm()
    {
        $this->reset([
            'transaction_date',
            'transaction_item_id',
            'description',
            'type',
            'amount',
            'status',
            'in_charge',
        ]);

        $this->isEditTransactionMode = false;
        $this->resetErrorBag();
    }

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

    public function boot(TransactionRepositoryInterface $transactionRepository, TransactionItemRepositoryInterface $transactionItemRepository, TransactionService $fileService,)
    {
        $this->fileService = $fileService;
        $this->transactionRepository = $transactionRepository;
        $this->transactionItemRepository = $transactionItemRepository;
    }

    public function removeFile($index)
    {
        unset($this->file[$index]);
        $this->file = array_values($this->file);
    }

    public function render()
    {
        $items = $this->transactionItemRepository
            ->all(['ordering' => 'asc']);
        return view('livewire.finance.transaction.actions-transaction', [
            'items' => $items
        ]);
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
            'transaction_date',
            'transaction_item_id',
            'description',
            'type',
            'amount',
            'file_name',
            'status',
            'in_charge',
        ]);

        try {

            if (!empty($this->file[0])) {
                $data['file_name'] = $this->fileService->store($this->file[0]);
            }

            $this->transactionRepository->create($data);

            session()->flash('success', 'Transaction tạo thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo transaction thất bại.' . $e->getMessage());
        }

        $this->redirectRoute('admin.finance.transactions', navigate: true);
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

            $this->transaction_date = $transaction->form_transaction_date;
            $this->amount = number_format($transaction->amount, 0, ',', '.');
            $this->transaction_item_id = $transaction->transaction_item_id;
            $this->description = $transaction->description;
            $this->type = $transaction->type;
            $this->existingFile = $transaction->file_name
            ? [
                'name' => basename($transaction->file_name),
                'url'  => $transaction->file_name,
            ]
            : null;

            // Hiển thị modal
            Flux::modal('action-transaction')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy transaction');
            return $this->redirectRoute('admin.finance.transactions', navigate: true);
        }
    }
    

    public function updateTransaction()
    {
        $this->amount = (int) str_replace(['.', ','], '', $this->amount);

        $this->validate();

        $data = $this->only([
            'transaction_date',
            'transaction_item_id',
            'description',
            'type',
            'amount',
            'file_name',
            'status',
            'in_charge',
        ]);

        try {

            if ($this->file) {
                // Upload file mới
                $data['file_name'] = $this->fileService->store($this->file);
            } elseif ($this->existingFile) {
                // Không upload file mới → giữ file cũ
                $data['file_name'] = basename($this->existingFile['name']);
            }
            
            $this->transactionRepository->update($this->transactionID, $data);

            session()->flash('success', 'Transaction cập nhật thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật transaction thất bại.' . $e->getMessage());
        }

        $this->redirectRoute('admin.finance.transactions', navigate: true);
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
            return $this->redirectRoute('admin.finance.transactions', navigate: true);
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

        $this->redirectRoute('admin.finance.transactions', navigate: true);
    }
}
