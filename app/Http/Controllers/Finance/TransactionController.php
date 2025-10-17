<?php

namespace App\Http\Controllers\Finance;

use Inertia\Inertia;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\TransactionFileService;
use App\Http\Requests\Finance\TransactionRequest;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Helpers\ResponseToastHelper;

class TransactionController extends Controller
{
    protected $transactionRepository;
    protected $fileService;

    public function __construct(TransactionRepositoryInterface $transactionRepository, TransactionFileService $fileService)
    {
        $this->transactionRepository = $transactionRepository;
        $this->fileService = $fileService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['createdByUser'])
            ->orderBy('transaction_date', 'desc');

        // Lọc theo ngày nếu có
        if ($request->filled('start_date')) {
            $query->where('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('transaction_date', '<=', $request->end_date);
        }

        // Lọc theo từ khóa tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('createdByUser', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // ✅ Clone query để tính tổng chính xác theo bộ lọc
        $summaryQuery = clone $query;

        $transactions = $query->paginate(15);

        // 🧮 Tính tổng thu / chi / số dư dựa trên query đã lọc
        $totalIncome = (clone $summaryQuery)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $summaryQuery)->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return Inertia::render('finance/transaction/index', [
            'transactions' => $transactions,
            'summary' => [
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'balance' => $balance,
            ],
            'filters' => [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'search' => $request->search,
            ],
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('finance/transaction/actions-transaction', [
            'mode' => 'create'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {

        try {
            DB::beginTransaction();

            // Create transaction
            $transaction = $this->transactionRepository->create([
                'transaction_date' => $request->transaction_date,
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'amount' => $request->amount,
                'file_name' => null,
                'created_by' => Auth::id(),
            ]);

            // Handle file upload
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                $file = is_array($files) ? $files[0] : $files;
                if ($file) {

                    $this->fileService->storeFile($file, $transaction->id);
                }
            }

            DB::commit();

            return ResponseToastHelper::successRedirect(
                'finance.transactions.index',
                'Giao dịch đã được tạo thành công.',
                ['title' => $request->title]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            
            return ResponseToastHelper::errorRedirect(
                'finance.transactions.index',
                'Có lỗi xảy ra khi tạo giao dịch: ' . $e->getMessage(),
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = $this->transactionRepository->find($id, ['createdByUser', 'files']);

        return Inertia::render('finance/show', [
            'transaction' => $transaction
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaction = $this->transactionRepository->find($id);

        if (!$transaction) {
            return redirect()->route('finance.transactions.index')
                ->with('error', 'Giao dịch không tồn tại');
        }

        $transaction->load(['createdByUser']);

        return Inertia::render('finance/transaction/actions-transaction', [
            'transaction' => $transaction,
            'mode' => 'edit'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $transaction = $this->transactionRepository->find($id);

            // 🧩 1. Cập nhật dữ liệu giao dịch
            $this->transactionRepository->update($id, [
                'transaction_date' => $request->transaction_date,
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'amount' => $request->amount,
            ]);

            // 🧩 2. Nếu có file được đánh dấu để xoá
            if ($request->has('deleted_files')) {
                foreach ($request->deleted_files as $transId) {
                    // chỉ xoá file của chính transaction hiện tại
                    if ($transaction->id == $transId) {
                        $this->fileService->deleteFile($transaction->id);
                    }
                }
            }

            // 🧩 3. Nếu có file mới được upload → thay thế file cũ
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                $file = is_array($files) ? $files[0] : $files;
                if ($file) {
                    $this->fileService->replaceFile($file, $transaction->id);
                }
            }

            DB::commit();

            return ResponseToastHelper::successRedirect(
                'finance.transactions.index',
                'Giao dịch ":title" đã được cập nhật thành công.',
                ['title' => $request->title]
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return ResponseToastHelper::errorRedirect(
                'finance.transactions.index',
                'Có lỗi xảy ra khi cập nhật giao dịch: ' . $e->getMessage(),
            );
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $transaction = $this->transactionRepository->find($id, ['files']);

            // Delete associated files from storage and database
            $this->fileService->deleteFile($transaction->id);

            $name = $transaction->title;
            // Delete transaction
            $this->transactionRepository->delete($id);

            DB::commit();

            return ResponseToastHelper::successRedirect(
                'finance.transactions.index',
                'Giao dịch ":title" đã được xóa thành công.',
                ['title' => $name]
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return ResponseToastHelper::errorRedirect(
                'finance.transactions.index',
                'Có lỗi xảy ra khi xóa giao dịch: ' . $e->getMessage(),
            );
        }
    }
}
