<?php

namespace App\Livewire\Finance\Transaction;

use Flux\Flux;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;

use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Storage;
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

    #[Validate('nullable|mimes:pdf|max:10240')]
    public $file;

    public $file_name;

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
            'existingFile',
        ]);

        $this->isEditTransactionMode = false;
        $this->resetErrorBag();
    }

    public function removeFile()
    {
        $this->file->delete();
        $this->file = null;
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

    public function render()
    {
        $items = $this->transactionItemRepository
            ->all(['ordering' => 'asc']);
        return view('livewire.finance.transaction.actions-transaction', [
            'items' => $items
        ]);
    }

    public function closeTransactionModal()
    {
        if ($this->file) {
            $this->removeFile();
        }
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

        if ($this->file) {
            $slugDescription = Str::slug($this->description);
            $newName = Carbon::parse($this->transaction_date)->format('Ymd') . '_' . $slugDescription . '_' . uniqid() . '.' . $this->file->getClientOriginalExtension();

            $storedPath = $this->file->storeAs('transactions', $newName, 'public');
            $this->file_name = $storedPath;

            $this->removeFile();
        }

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

            $this->transactionRepository->create($data);

            Flux::toast(
                heading: 'Thành công!',
                text: 'Tiền quỹ đã được tạo thành công.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Đã xảy ra lỗi!',
                text: 'Không thể tạo tiền quỹ. ' . (app()->environment('local') ? $e->getMessage() : 'Vui lòng thử lại sau.'),
                variant: 'error',
            );
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
            $this->existingFile = $transaction->file_name;

            // Hiển thị modal
            Flux::modal('action-transaction')->show();
        } else {
            // Nếu không tìm thấy
            Flux::toast(
                heading: 'Không tìm thấy!',
                text: 'Tiền quỹ không tồn tại.',
                variant: 'error',
            );

            return $this->redirectRoute('admin.finance.transactions', navigate: true);
        }
    }

    public function removeExistingFile()
    {
        $this->existingFile = null;
    }

    public function updateTransaction()
    {

        $this->amount = (int) str_replace(['.', ','], '', $this->amount);

        $this->validate();

        $transaction = $this->transactionRepository->find($this->transactionID);

        if ($this->file) {

            if ($transaction && $transaction->file_name) {
                Storage::disk('public')->delete($transaction->file_name);
            }

            $slugDescription = Str::slug($this->description);
            $newName = Carbon::parse($this->transaction_date)->format('Ymd') . '_' . $slugDescription . '_' . uniqid() . '.' . $this->file->getClientOriginalExtension();

            $storedPath = $this->file->storeAs('transactions', $newName, 'public');
            $this->file_name = $storedPath;

            $this->removeFile();
        } elseif (!$this->existingFile) {

            $this->file_name = null;

            if ($transaction && $transaction->file_name) {
                Storage::disk('public')->delete($transaction->file_name);
            }
        } else {
            if ($transaction && $transaction->file_name) {

                $descriptionChanged = $this->description !== $transaction->description;
                $dateChanged = $this->transaction_date !== $transaction->transaction_date->format('Y-m-d');

                if ($descriptionChanged || $dateChanged) {

                    // Tạo tên file mới
                    $slugDescription = Str::slug($this->description);
                    $newName = Carbon::parse($this->transaction_date)->format('Ymd')
                        . '_' . $slugDescription . '_' . uniqid()
                        . '.' . pathinfo($transaction->file_name, PATHINFO_EXTENSION);

                    // Đổi tên file vật lý
                    Storage::disk('public')->move(
                        $transaction->file_name,
                        'transactions/' . $newName
                    );

                    // Lưu lại file_name mới
                    $this->file_name = 'transactions/' . $newName;
                } else {
                    // Không thay đổi ngày / mô tả → giữ nguyên file
                    $this->file_name = $this->existingFile;
                }
            } else {
                $this->file_name = null;
            }
        }



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
            $this->transactionRepository->update($this->transactionID, $data);

            Flux::toast(
                heading: 'Đã lưu thay đổi',
                text: 'Tiền quỹ cập nhật thành công.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Cập nhật thất bại!',
                text: app()->environment('local')
                    ? $e->getMessage()
                    : 'Không thể cập nhật tiền quỹ.',
                variant: 'error',
            );
        }

        $this->redirectRoute('admin.finance.transactions', navigate: true);
    }


    #[On('deleteTransaction')]
    public function deleteTransaction($id)
    {

        $this->resetForm();

        $transaction = $this->transactionRepository->find($id);

        if ($transaction) {
            $this->transactionID = $transaction->id;

            Flux::modal('delete-transaction')->show();
        } else {

            Flux::toast(
                heading: 'Không tìm thấy!',
                text: 'Tiền quỹ không tồn tại.',
                variant: 'error',
            );
            return $this->redirectRoute('admin.finance.transactions', navigate: true);
        }
    }

    public function deleteTransactionConfirm()
    {
        try {

            $transaction = $this->transactionRepository->find($this->transactionID);

            if ($transaction && $transaction->file_name) {
                Storage::disk('public')->delete($transaction->file_name);
            }

            $this->transactionRepository->delete($this->transactionID);

            Flux::toast(
                heading: 'Đã xoá!',
                text: 'Tiền quỹ đã được xoá thành công.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Xoá thất bại!',
                text: app()->environment('local')
                    ? $e->getMessage()
                    : 'Không thể xoá tiền quỹ. Vui lòng thử lại.',
                variant: 'error',
            );
        }

        $this->redirectRoute('admin.finance.transactions', navigate: true);
    }
}
