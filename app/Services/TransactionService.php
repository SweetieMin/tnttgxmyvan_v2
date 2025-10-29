<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\TransactionItem;

class TransactionService
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

    public function generateName(?string $itemId = null): string
    {
        // 🔹 Lấy tên hạng mục (nếu có)
        $itemName = 'Tat-ca'; // mặc định

        if (!empty($itemId)) {
            $item = TransactionItem::find($itemId);
            if ($item) {
                // Chuyển tiếng Việt có dấu → không dấu, snake-case
                $itemName = $this->slugify($item->name);
            }
        }

        // 🔹 Ngày hiện tại
        $date = Carbon::now()->format('dmY');

        // 🔹 Ghép tên chuẩn
        return "Thong-ke-tien-quy-{$itemName}-{$date}.xlsx";
    }

    protected function slugify(string $text): string
    {
        $text = strtolower($text);
        $text = str_replace(
            ['à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ',
             'è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ',
             'ì','í','ị','ỉ','ĩ',
             'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ',
             'ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ',
             'ỳ','ý','ỵ','ỷ','ỹ',
             'đ'],
            ['a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
             'e','e','e','e','e','e','e','e','e','e','e',
             'i','i','i','i','i',
             'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
             'u','u','u','u','u','u','u','u','u','u','u',
             'y','y','y','y','y',
             'd'],
            $text
        );
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        $text = trim($text, '-');
        return $text;
    }
}
