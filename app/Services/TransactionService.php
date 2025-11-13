<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\TransactionItem;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TransactionService
{
    /**
     * Lưu file mới vào thư mục storage/app/public/transactions
     * và xoá file tạm của Livewire
     */
    public function storeFile(?TemporaryUploadedFile $file): ?string
    {
        if (!$file) {
            return null;
        }

        // Lưu vào storage/app/public/transactions
        $storedPath = $file->store('transactions', 'public');

        // Xoá file tạm của livewire-tmp
        $file->delete();

        // Trả về tên file
        return basename($storedPath);
    }

    /**
     * Xoá file cũ khỏi storage/app/public/transactions
     */
    public function deleteFile(?string $fileName): void
    {
        if ($fileName) {
            Storage::disk('public')->delete('transactions/' . $fileName);
        }
    }

    /**
     * Xử lý upload file khi UPDATE:
     * - Nếu có file mới → xoá file cũ + lưu file mới
     * - Nếu user xoá file → xoá file cũ
     * - Nếu giữ nguyên → trả về file cũ
     */
    public function handleUpdateFile($file, $existingFile, $transaction): ?string
    {
        // Nếu upload file mới
        if ($file) {
            // Xoá file cũ nếu có
            $this->deleteFile($transaction->file_name);

            // Lưu file mới
            return $this->storeFile($file);
        }

        // Nếu người dùng xoá file cũ
        if (!$existingFile) {
            $this->deleteFile($transaction->file_name);
            return null;
        }

        // Nếu giữ nguyên file
        return $existingFile;
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

    public function generateName(string|array|null $itemId = null): string
    {
        // Nếu không có filter → mặc định
        if (empty($itemId)) {
            $itemName = 'Tat-ca';
        } else {
            // Convert về array
            $itemIds = (array) $itemId;

            // Lấy tất cả tên item
            $items = TransactionItem::whereIn('id', $itemIds)->pluck('name')->toArray();

            // Nếu tìm được → ghép lại thành slug
            if (!empty($items)) {
                $itemName = $this->slugify(implode('-', $items));
            } else {
                $itemName = 'Tat-ca';
            }
        }

        // Ngày hiện tại
        $date = now()->format('dmY');

        return "Thong-ke-tien-quy-{$itemName}-{$date}.xlsx";
    }

    protected function slugify(string $text): string
    {
        $text = strtolower($text);
        $text = str_replace(
            [
                'à',
                'á',
                'ạ',
                'ả',
                'ã',
                'â',
                'ầ',
                'ấ',
                'ậ',
                'ẩ',
                'ẫ',
                'ă',
                'ằ',
                'ắ',
                'ặ',
                'ẳ',
                'ẵ',
                'è',
                'é',
                'ẹ',
                'ẻ',
                'ẽ',
                'ê',
                'ề',
                'ế',
                'ệ',
                'ể',
                'ễ',
                'ì',
                'í',
                'ị',
                'ỉ',
                'ĩ',
                'ò',
                'ó',
                'ọ',
                'ỏ',
                'õ',
                'ô',
                'ồ',
                'ố',
                'ộ',
                'ổ',
                'ỗ',
                'ơ',
                'ờ',
                'ớ',
                'ợ',
                'ở',
                'ỡ',
                'ù',
                'ú',
                'ụ',
                'ủ',
                'ũ',
                'ư',
                'ừ',
                'ứ',
                'ự',
                'ử',
                'ữ',
                'ỳ',
                'ý',
                'ỵ',
                'ỷ',
                'ỹ',
                'đ'
            ],
            [
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'a',
                'e',
                'e',
                'e',
                'e',
                'e',
                'e',
                'e',
                'e',
                'e',
                'e',
                'e',
                'i',
                'i',
                'i',
                'i',
                'i',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'o',
                'u',
                'u',
                'u',
                'u',
                'u',
                'u',
                'u',
                'u',
                'u',
                'u',
                'u',
                'y',
                'y',
                'y',
                'y',
                'y',
                'd'
            ],
            $text
        );
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        $text = trim($text, '-');
        return $text;
    }
}
