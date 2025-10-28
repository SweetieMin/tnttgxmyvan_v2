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
        // Nếu Livewire Dropzone trả về array
        if (is_array($file) && isset($file['path'])) {
            $sourcePath = $file['path'];
        }
        // Nếu là UploadedFile thật (trường hợp dùng <input type="file">)
        elseif ($file instanceof UploadedFile) {
            $sourcePath = $file->getRealPath();
        } else {
            throw new \InvalidArgumentException('File không hợp lệ.');
        }

        // ✅ Tạo tên file duy nhất
        $uniqueName = (string) Str::uuid() . '.pdf';
        $destination = 'transactions/' . $uniqueName;
        Storage::disk('public')->put($destination, file_get_contents($sourcePath));

        if (is_array($file) && isset($file['path']) && file_exists($file['path'])) {
            @unlink($file['path']);
        }

        return $uniqueName; // Trả về path để lưu DB
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
