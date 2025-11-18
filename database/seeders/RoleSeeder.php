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
            // Cấp hệ thống
            ['name' => 'admin', 'type' => 'system' ,'description' => 'Quản trị toàn bộ hệ thống, chịu trách nhiệm bảo trì và hỗ trợ kỹ thuật cho đoàn.', 'ordering' => 1],
        
            // Cấp linh hướng
            ['name' => 'Cha Tuyên Úy', 'type' => 'spiritual',  'description' => 'Linh mục linh hướng của xứ đoàn, chịu trách nhiệm hướng dẫn thiêng liêng và định hướng chung cho toàn đoàn.', 'ordering' => 2],
            ['name' => 'Thầy Phó Tế', 'type' => 'spiritual',  'description' => 'Phó tế hoặc chủng sinh được Cha Tuyên Úy ủy quyền hỗ trợ trong các hoạt động mục vụ và hướng dẫn đoàn.', 'ordering' => 3],
        
            // Cấp giáo lý
            ['name' => 'Trưởng Giáo Lý', 'type' => 'catechist',  'description' => 'Phụ trách toàn bộ chương trình giáo lý, sắp xếp nhân sự giảng dạy và nội dung học tập.', 'ordering' => 4],
            ['name' => 'Phó Giáo Lý', 'type' => 'catechist',  'description' => 'Hỗ trợ Trưởng Giáo Lý trong việc điều phối và quản lý giáo lý viên.', 'ordering' => 5],
            ['name' => 'Giáo Lý Viên', 'type' => 'catechist',  'description' => 'Trực tiếp giảng dạy và hướng dẫn các em học giáo lý, phối hợp cùng huynh trưởng trong lớp học.', 'ordering' => 6],
        
            // Cấp điều hành xứ đoàn
            ['name' => 'Xứ Đoàn Trưởng', 'type' => 'scouter',  'description' => 'Đứng đầu xứ đoàn, quản lý và điều hành mọi hoạt động mục vụ, giáo lý và sinh hoạt thiếu nhi.', 'ordering' => 7],
            ['name' => 'Xứ Đoàn Phó', 'type' => 'scouter',  'description' => 'Hỗ trợ Xứ Đoàn Trưởng trong công tác điều hành và đại diện khi được ủy quyền.', 'ordering' => 8],
            ['name' => 'Thủ Quỹ', 'type' => 'scouter',  'description' => 'Quản lý thu chi trong Đoàn. Cân đối ngân sách. Báo cáo quỹ theo mùa..', 'ordering' => 9],
        
            // Cấp ngành Nghĩa
            ['name' => 'Trưởng Ngành Nghĩa', 'type' => 'scouter',  'description' => 'Phụ trách và điều hành toàn bộ ngành Nghĩa trong xứ đoàn.', 'ordering' => 10],
            ['name' => 'Phó Ngành Nghĩa', 'type' => 'scouter',  'description' => 'Hỗ trợ Trưởng Ngành Nghĩa trong việc quản lý, sinh hoạt và đào tạo huynh trưởng ngành.', 'ordering' => 11],
        
            // Cấp ngành Thiếu
            ['name' => 'Trưởng Ngành Thiếu', 'type' => 'scouter',  'description' => 'Phụ trách và điều hành toàn bộ ngành Thiếu trong xứ đoàn.', 'ordering' => 12],
            ['name' => 'Phó Ngành Thiếu', 'type' => 'scouter',  'description' => 'Hỗ trợ Trưởng Ngành Thiếu trong công tác tổ chức và quản lý huynh trưởng ngành.', 'ordering' => 13],
        
            // Cấp ngành Ấu
            ['name' => 'Trưởng Ngành Ấu', 'type' => 'scouter',  'description' => 'Phụ trách và điều hành toàn bộ ngành Ấu trong xứ đoàn.', 'ordering' => 14],
            ['name' => 'Phó Ngành Ấu', 'type' => 'scouter',  'description' => 'Hỗ trợ Trưởng Ngành Ấu trong việc điều hành sinh hoạt và chăm sóc các em.', 'ordering' => 15],
        
            // Cấp ngành Tiền Ấu
            ['name' => 'Trưởng Ngành Tiền Ấu', 'type' => 'scouter',  'description' => 'Phụ trách và điều hành toàn bộ ngành Tiền Ấu trong xứ đoàn.', 'ordering' => 16],
            ['name' => 'Phó Ngành Tiền Ấu', 'type' => 'scouter',  'description' => 'Hỗ trợ Trưởng Ngành Tiền Ấu trong công tác giảng dạy và tổ chức sinh hoạt.', 'ordering' => 17],
        
            // Cấp huynh trưởng
            ['name' => 'Huynh Trưởng', 'type' => 'scouter',  'description' => 'Phụ trách hướng dẫn, sinh hoạt và đồng hành với thiếu nhi trong ngành.', 'ordering' => 18],
            ['name' => 'Dự Trưởng', 'type' => 'scouter',  'description' => 'Huynh trưởng dự bị, hỗ trợ huynh trưởng chính và học hỏi thêm kỹ năng lãnh đạo.', 'ordering' => 19],
        
            // Cấp đội trưởng và thiếu nhi
            ['name' => 'Đội Trưởng', 'type' => 'scouter',  'description' => 'Phụ trách một đội nhỏ trong ngành, dẫn dắt các thành viên trong các buổi sinh hoạt và rèn luyện.', 'ordering' => 20],
            ['name' => 'Thiếu Nhi', 'type' => 'children',  'description' => 'Thành viên của xứ đoàn, tham gia học tập, sinh hoạt và rèn luyện nhân bản – đức tin.', 'ordering' => 21],
        ];
        

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
