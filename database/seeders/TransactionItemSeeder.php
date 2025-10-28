<?php

namespace Database\Seeders;

use App\Models\TransactionItem;
use Illuminate\Database\Seeder;

class TransactionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['is_system' => 1, 'name' => 'Tết', 'description' => 'Thu / chi dịp Tết.', 'ordering' => 2],
            ['is_system' => 1, 'name' => 'Hỗ trợ', 'description' => 'Chi hỗ trợ thành viên.', 'ordering' => 3],
            ['is_system' => 1, 'name' => 'Trại', 'description' => 'Thu / chi các kỳ trại.', 'ordering' => 4],
            ['is_system' => 1, 'name' => 'Trung thu', 'description' => 'Thu chi Trung Thu.', 'ordering' => 5],
            ['is_system' => 1, 'name' => 'Bổn mạng', 'description' => 'Thu chi lễ Bổn mạng Xứ đoàn.', 'ordering' => 6],
            ['is_system' => 1, 'name' => 'Giáng sinh', 'description' => 'Thu chi lễ Noel.', 'ordering' => 7], // ✅ ngắn gọn
            ['is_system' => 0, 'name' => 'Đồng phục', 'description' => 'Thu chi đồng phục.', 'ordering' => 8],
            ['is_system' => 0, 'name' => 'Ngày của Cha/Mẹ', 'description' => 'Thu chi hoạt động tri ân cha mẹ.', 'ordering' => 9],
            ['is_system' => 0, 'name' => 'Hội thao', 'description' => 'Thu / chi hội thao.', 'ordering' => 10],
        ];

        foreach ($items as $item) {
            TransactionItem::create($item);
        }
    }
}
