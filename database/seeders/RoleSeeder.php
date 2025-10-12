<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Quản trị toàn bộ hệ thống và sửa chữa hệ thống theo nhu cầu của đoàn.', 'ordering' => 1],
            ['name' => 'Cha Tuyên Úy', 'description' => 'Người đứng đầu xứ đoàn, chịu trách nhiệm quản lý và điều hành hoạt động của xứ đoàn.', 'ordering' => 2],
            ['name' => 'Xứ Đoàn Trưởng', 'description' => 'Đứng đầu xứ đoàn, chịu trách nhiệm quản lý và điều hành hoạt động của xứ đoàn.', 'ordering' => 3],
            ['name' => 'Xứ Đoàn Phó', 'description' => 'Hỗ trợ Xứ Đoàn Trưởng trong việc điều hành và quản lý các hoạt động của xứ đoàn.', 'ordering' => 4],
            ['name' => 'Trưởng Ngành Nghĩa', 'description' => 'Đứng đầu và điều hành ngành Nghĩa trong xứ đoàn.', 'ordering' => 5],
            ['name' => 'Phó Ngành Nghĩa', 'description' => 'Hỗ trợ Trưởng Ngành Nghĩa trong việc quản lý và điều hành ngành.', 'ordering' => 6],
            ['name' => 'Trưởng Ngành Thiếu', 'description' => 'Đứng đầu và điều hành ngành Thiếu trong xứ đoàn.', 'ordering' => 7],
            ['name' => 'Phó Ngành Thiếu', 'description' => 'Hỗ trợ Trưởng Ngành Thiếu trong việc quản lý và điều hành ngành.', 'ordering' => 8],
            ['name' => 'Trưởng Ngành Ấu', 'description' => 'Đứng đầu và điều hành ngành Ấu trong xứ đoàn.', 'ordering' => 9],
            ['name' => 'Phó Ngành Ấu', 'description' => 'Hỗ trợ Trưởng Ngành Ấu trong việc quản lý và điều hành ngành.', 'ordering' => 10],
            ['name' => 'Trưởng Ngành Tiền Ấu', 'description' => 'Đứng đầu và điều hành ngành Tiền Ấu trong xứ đoàn.', 'ordering' => 11],
            ['name' => 'Phó Ngành Tiền Ấu', 'description' => 'Hỗ trợ Trưởng Ngành Tiền Ấu trong việc quản lý và điều hành ngành.', 'ordering' => 12],
            ['name' => 'Huynh Trưởng', 'description' => 'Hướng dẫn và quản lý các thành viên trong ngành, đồng hành cùng thiếu nhi trong việc phát triển.', 'ordering' => 13],
            ['name' => 'Dự Trưởng', 'description' => 'Hỗ trợ Huynh Trưởng trong việc quản lý các thành viên, thường là huynh trưởng dự bị.', 'ordering' => 14],
            ['name' => 'Đội Trưởng', 'description' => 'Quản lý và dẫn dắt một đội nhỏ trong ngành, đảm bảo các hoạt động được thực hiện đúng kế hoạch.', 'ordering' => 15],
            ['name' => 'Thiếu Nhi', 'description' => 'Thành viên nhỏ tuổi của xứ đoàn, tham gia các hoạt động giáo dục và sinh hoạt.', 'ordering' => 16],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
