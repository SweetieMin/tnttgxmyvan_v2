<?php

namespace App\Exports;

use App\Models\Transaction;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TransactionExport implements
    FromCollection,
    WithHeadings,
    WithColumnFormatting,
    WithTitle,
    ShouldAutoSize,
    WithEvents
{
    protected ?string $search;
    protected ?array $item;
    protected ?string $startDate;
    protected ?string $endDate;

    public function __construct(
        ?string $search,
        array|string|null $item,
        ?string $startDate,
        ?string $endDate
    ) {
        $this->search = $search;
        $this->item = (array) $item; // Convert luôn về array
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    

    /**
     * 🔍 Lấy dữ liệu có filter
     */
    public function collection()
    {
        $query = Transaction::with('item')
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at');

        // Nếu có tìm kiếm theo mô tả
        if (!empty($this->search)) {
            $query->where('description', 'like', '%' . $this->search . '%');
        }

        // Nếu có chọn hạng mục cụ thể
        if (!empty($this->item)) {
            $query->whereIn('transaction_item_id', $this->item);
        }

        // 📅 Nếu có khoảng ngày
        if (!empty($this->startDate) && !empty($this->endDate)) {
            $query->whereBetween('transaction_date', [$this->startDate, $this->endDate]);
        } elseif (!empty($this->startDate)) {
            $query->whereDate('transaction_date', '>=', $this->startDate);
        } elseif (!empty($this->endDate)) {
            $query->whereDate('transaction_date', '<=', $this->endDate);
        }

        // Trả dữ liệu dạng collection
        return $query->get()->map(function ($transaction) {
            return [
                'Ngày' => $transaction->transaction_date
                    ? ExcelDate::stringToExcel($transaction->transaction_date->format('Y-m-d'))
                    : null,

                'Hạng mục' => optional($transaction->item)->name ?? 'Khác',

                'Mô tả' => $transaction->description ?? '',

                'Thu' => $transaction->type === 'income' ? $transaction->amount : null,

                'Chi' => $transaction->type === 'expense' ? $transaction->amount : null,

                'Người phụ trách' => $transaction->in_charge ?? '',

                'Trạng thái' => $transaction->status === 'pending' ? 'Chưa chi' : 'Đã chi',

                'File đính kèm' => $transaction->file_name
                    ? asset($transaction->file_name)
                    : '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Ngày',
            'Hạng mục',
            'Mô tả',
            'Thu',
            'Chi',
            'Người phụ trách',
            'Trạng thái',
            'File đính kèm',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'D' => '#,##0_);[Red](#,##0_)',
            'E' => '#,##0_);[Red](#,##0_)',
        ];
    }

    public function title(): string
    {
        return 'Báo cáo Giao dịch';
    }

    /**
     * 🎯 Thêm hàng “Tổng cộng” và “Còn lại”
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                /** @var Worksheet $sheet */
                $sheet = $event->sheet->getDelegate();

                $highestRow = $sheet->getHighestRow();

                // 🟣 Duyệt tất cả hàng dữ liệu (bỏ header)
                for ($row = 2; $row <= $highestRow; $row++) {
                    $fileCell = "H{$row}";
                    $fileUrl  = $sheet->getCell($fileCell)->getValue();

                    // Nếu có URL thì đổi thành hyperlink
                    if (!empty($fileUrl)) {
                        // Chỉ hiển thị "Xem file", link thật vẫn gắn vào
                        $sheet->getCell($fileCell)
                            ->getHyperlink()
                            ->setUrl($fileUrl);

                        $sheet->setCellValue($fileCell, 'Xem file');
                        $sheet->getStyle($fileCell)
                            ->getFont()
                            ->setUnderline(true)
                            ->getColor()
                            ->setARGB('FF1E90FF'); // xanh dương
                    }
                }

                // === 1️⃣ Dòng “Tổng cộng” ===
                $lastRow = $highestRow + 1;
                $sheet->setCellValue("C{$lastRow}", 'Tổng cộng:');
                $sheet->setCellValue("D{$lastRow}", "=SUM(D2:D" . ($lastRow - 1) . ")");
                $sheet->setCellValue("E{$lastRow}", "=SUM(E2:E" . ($lastRow - 1) . ")");

                $sheet->getStyle("C{$lastRow}:E{$lastRow}")->getFont()->setBold(true);
                $sheet->getStyle("C{$lastRow}:E{$lastRow}")
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFDE68A');

                // === 2️⃣ Dòng “Còn lại” ===
                $balanceRow = $lastRow + 1;
                $sheet->setCellValue("C{$balanceRow}", 'Còn lại:');
                $sheet->mergeCells("D{$balanceRow}:E{$balanceRow}");
                $sheet->setCellValue("D{$balanceRow}", "=D{$lastRow}-E{$lastRow}");

                $sheet->getStyle("C{$balanceRow}:E{$balanceRow}")->getFont()->setBold(true);
                $sheet->getStyle("C{$balanceRow}:E{$balanceRow}")
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFD9F99D');
                $sheet->getStyle("D{$balanceRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0;[Red]-#,##0');
                $sheet->getStyle("D{$balanceRow}")
                    ->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
