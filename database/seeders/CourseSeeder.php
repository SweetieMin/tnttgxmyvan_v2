<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            ['name' => 'Khai Tâm 1', 'description' => 'Lớp giáo lý dành cho các em mới bắt đầu tìm hiểu về Đức tin Công giáo, với nội dung giới thiệu về Thiên Chúa, Đức Mẹ Maria và các thánh.', 'ordering' => 1],
            ['name' => 'Khai Tâm 2', 'description' => 'Tiếp nối từ Khai Tâm 1, giúp các em hiểu sâu hơn về các bí tích và những khía cạnh cơ bản của đời sống Kitô hữu.', 'ordering' => 2],
            ['name' => 'Khai Tâm 3', 'description' => 'Giai đoạn cuối của Khai Tâm, tập trung vào việc giúp các em chuẩn bị sẵn sàng để đón nhận các bí tích, đặc biệt là bí tích Thánh Thể.', 'ordering' => 3],
            ['name' => 'Xưng Tội 1', 'description' => 'Lớp giáo lý chuẩn bị cho các em lần đầu tiên lãnh nhận bí tích Giải Tội, giúp các em hiểu về sự sám hối và ơn tha thứ của Chúa.', 'ordering' => 4],
            ['name' => 'Xưng Tội 2', 'description' => 'Tiếp tục rèn luyện việc thực hành bí tích Giải Tội, hướng dẫn các em sống đời sống Kitô hữu trong sự hòa giải và thánh thiện.', 'ordering' => 5],
            ['name' => 'Thêm Sức 1', 'description' => 'Lớp giáo lý chuẩn bị cho các em lãnh nhận bí tích Thêm Sức, giúp các em hiểu về vai trò của Chúa Thánh Thần trong đời sống của mình.', 'ordering' => 6],
            ['name' => 'Thêm Sức 2', 'description' => 'Tiếp nối Thêm Sức 1, giúp các em nhận thức sâu sắc hơn về ơn gọi và trách nhiệm của người Kitô hữu sau khi nhận bí tích Thêm Sức.', 'ordering' => 7],
            ['name' => 'Thêm Sức 2A', 'description' => 'Lớp giáo lý nâng cao dành cho những em đã hoàn thành Thêm Sức 2, tập trung vào việc sống đời Kitô hữu trưởng thành.', 'ordering' => 8],
            ['name' => 'Thêm Sức 2B', 'description' => 'Lớp giáo lý tương tự Thêm Sức 2A nhưng nhấn mạnh vào việc phục vụ cộng đồng và đời sống thánh thiện trong gia đình.', 'ordering' => 9],
            ['name' => 'Thêm Sức 3A', 'description' => 'Tiếp tục phát triển từ Thêm Sức 2A, giúp các em củng cố đức tin và chuẩn bị cho các thách thức trong đời sống đạo.', 'ordering' => 10],
            ['name' => 'Thêm Sức 3B', 'description' => 'Lớp giáo lý nâng cao dành cho những em mong muốn phục vụ trong vai trò lãnh đạo tại các cộng đoàn giáo xứ.', 'ordering' => 11],
            ['name' => 'Kinh Thánh 1', 'description' => 'Lớp giáo lý giới thiệu về Kinh Thánh, giúp các em nắm vững các câu chuyện chính và ý nghĩa của Cựu Ước.', 'ordering' => 12],
            ['name' => 'Kinh Thánh 2', 'description' => 'Tiếp nối Kinh Thánh 1, giúp các em hiểu sâu hơn về Tân Ước và vai trò của Chúa Giêsu trong lịch sử cứu độ.', 'ordering' => 13],
            ['name' => 'Bao Đồng 1', 'description' => 'Lớp giáo lý chuẩn bị cho các em lãnh nhận nghi thức Bao Đồng, giúp các em tái khẳng định đức tin Kitô giáo trước cộng đoàn.', 'ordering' => 14],
            ['name' => 'Bao Đồng 2', 'description' => 'Tiếp tục từ Bao Đồng 1, giúp các em củng cố đức tin và chuẩn bị sẵn sàng để sống đời sống Kitô hữu trưởng thành.', 'ordering' => 15],
        ];
    }
}
