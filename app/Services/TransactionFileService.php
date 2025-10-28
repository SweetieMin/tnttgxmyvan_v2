<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TransactionFileService
{
    /**
     * Lưu file PDF giao dịch vào storage
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return string|null  Đường dẫn file đã lưu (ví dụ: transactions/uniquename.pdf)
     */
    public function store($file): ?string
    {
        // 🎯 Xác định đường dẫn gốc
        if (is_array($file)) {
            if (isset($file[0]['path'])) {
                // Dropzone upload
                $sourcePath = $file[0]['path'];
            } elseif (isset($file['path'])) {
                // Một mảng đơn
                $sourcePath = $file['path'];
            } else {
                throw new \InvalidArgumentException('File không hợp lệ (thiếu path).');
            }
        } elseif ($file instanceof UploadedFile) {
            // Input type="file"
            $sourcePath = $file->getRealPath();
        } else {
            throw new \InvalidArgumentException('File không hợp lệ.');
        }
    
        // 🔒 Chỉ cho phép PDF
        if (strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION)) !== 'pdf') {
            throw new \InvalidArgumentException('Chỉ cho phép file PDF.');
        }
    
        // ✅ Tạo tên file duy nhất và lưu
        $uniqueName = (string) Str::uuid() . '.pdf';
        $destination = 'transactions/' . $uniqueName;
    
        Storage::disk('public')->put($destination, file_get_contents($sourcePath));
    
        // ✅ Xóa file tạm nếu tồn tại (để dọn livewire-tmp)
        if (file_exists($sourcePath)) {
            @unlink($sourcePath);
        }
    
        return $uniqueName;
    }
    


    /**
     * Xoá file nếu tồn tại
     */
    public function delete(?string $fileName): void
    {
        if ($fileName && Storage::disk('public')->exists('transactions/' . $fileName)) {
            Storage::disk('public')->delete('transactions/' . $fileName);
        }
    }
}
