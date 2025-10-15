<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TransactionFileService
{
    /**
     * Lưu file PDF vào storage và cập nhật transaction
     *
     * @param UploadedFile $file File được upload
     * @param int $transactionId ID của transaction
     * @return string|null Tên file đã được lưu
     */
    public function storeFile(UploadedFile $file, int $transactionId): ?string
    {
        \Log::info('TransactionFileService: storeFile called', [
            'transaction_id' => $transactionId,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'is_valid' => $file->isValid()
        ]);

        if (!$file->isValid()) {
            \Log::error('File is not valid');
            return null;
        }

        // Tạo tên file unique
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $uniqueFileName = $this->generateUniqueFileName($originalName, $extension);
        
        \Log::info('Generated unique filename:', ['unique_file_name' => $uniqueFileName]);
        
        // Lưu file vào storage
        $filePath = $file->storeAs('transactions', $uniqueFileName, 'public');
        
        \Log::info('File stored:', ['file_path' => $filePath]);
        
        if ($filePath) {
            // Cập nhật transaction với tên file
            $updated = Transaction::where('id', $transactionId)->update([
                'file_name' => $uniqueFileName
            ]);
            
            \Log::info('Transaction updated:', [
                'transaction_id' => $transactionId,
                'file_name' => $uniqueFileName,
                'updated' => $updated
            ]);
            
            return $uniqueFileName;
        }

        \Log::error('Failed to store file');
        return null;
    }

    /**
     * Xóa file từ storage và cập nhật transaction
     *
     * @param int $transactionId ID của transaction
     * @return bool
     */
    public function deleteFile(int $transactionId): bool
    {
        $transaction = Transaction::find($transactionId);
        
        if (!$transaction || !$transaction->file_name) {
            return false;
        }
    
        $filePath = 'transactions/' . $transaction->file_name;
    
        // ✅ Dùng disk('public'), KHÔNG thêm "public/" trong path
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            Log::info('Deleted file from storage', ['file_path' => $filePath]);
        } else {
            Log::warning('File not found in storage', ['file_path' => $filePath]);
        }
        
        // Cập nhật transaction (xóa file_name)
        $transaction->update(['file_name' => null]);
    
        return true;
    }
    

    /**
     * Thay thế file cũ bằng file mới
     *
     * @param UploadedFile $file File mới
     * @param int $transactionId ID của transaction
     * @return string|null Tên file mới đã được lưu
     */
    public function replaceFile(UploadedFile $file, int $transactionId): ?string
    {
        $transaction = Transaction::find($transactionId);
        
        if (!$transaction) {
            return null;
        }

        // Xóa file cũ nếu có
        if ($transaction->file_name && Storage::exists('public/transactions/' . $transaction->file_name)) {
            Storage::delete('public/transactions/' . $transaction->file_name);
        }

        // Lưu file mới
        return $this->storeFile($file, $transactionId);
    }

    /**
     * Tạo tên file unique
     *
     * @param string $originalName
     * @param string $extension
     * @return string
     */
    private function generateUniqueFileName(string $originalName, string $extension): string
    {
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $baseName = Str::slug($baseName); // Chuyển đổi thành slug để tránh ký tự đặc biệt
        
        // Tạo tên file unique với timestamp và random string
        $uniqueString = time() . '_' . Str::random(10);
        
        return $baseName . '_' . $uniqueString . '.' . $extension;
    }

    /**
     * Lấy đường dẫn đầy đủ của file
     *
     * @param string $fileName
     * @return string
     */
    public function getFileUrl(string $fileName): string
    {
        return Storage::url('transactions/' . $fileName);
    }

    /**
     * Kiểm tra file có tồn tại không
     *
     * @param string $fileName
     * @return bool
     */
    public function fileExists(string $fileName): bool
    {
        return Storage::exists('public/transactions/' . $fileName);
    }
}
