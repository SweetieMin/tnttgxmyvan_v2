<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            ['course' => 'Khai Tâm 1', 'sector' => 'Tiền Ấu 1'],
            ['course' => 'Khai Tâm 2', 'sector' => 'Tiền Ấu 2'],
            ['course' => 'Khai Tâm 3', 'sector' => 'Tiền Ấu 3'],
            ['course' => 'Xưng Tội 1', 'sector' => 'Ấu 1'],
            ['course' => 'Xưng Tội 2', 'sector' => 'Ấu 2'],
            ['course' => 'Thêm Sức 1', 'sector' => 'Ấu 3'],
            ['course' => 'Thêm Sức 2', 'sector' => 'Thiếu 1'],
            ['course' => 'Thêm Sức 3', 'sector' => 'Thiếu 2'],
            ['course' => 'Kinh Thánh 1', 'sector' => 'Thiếu 3'],
            ['course' => 'Kinh Thánh 2', 'sector' => 'Nghĩa 1'],
            ['course' => 'Kinh Thánh 3', 'sector' => 'Nghĩa 2'],
            ['course' => 'Bao Đồng 1', 'sector' => 'Nghĩa 3'],
        ];

        $age = 5;
        $ordering = 1;

        foreach ($programs as $program) {
            Program::create([
                'ordering'    => $ordering++,
                'course'   => $program['course'],
                'sector'    => $program['sector'],
                'description' => "Độ tuổi: {$age} tuổi",
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $age++; // tăng thêm 1 tuổi mỗi dòng
        }
    }
}
