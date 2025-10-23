<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;


class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            ['academic_year_id' => 1, 'program_id' => 1, 'course' => 'Khai Tâm 1',  'ordering' => 1],
            ['academic_year_id' => 1, 'program_id' => 2, 'course' => 'Khai Tâm 2',  'ordering' => 2],
            ['academic_year_id' => 1, 'program_id' => 3, 'course' => 'Khai Tâm 3',  'ordering' => 3],
            ['academic_year_id' => 1, 'program_id' => 4, 'course' => 'Xưng Tội 1',  'ordering' => 4],
            ['academic_year_id' => 1, 'program_id' => 5, 'course' => 'Xưng Tội 2', 'ordering' => 5],
            ['academic_year_id' => 1, 'program_id' => 6, 'course' => 'Thêm Sức 1',  'ordering' => 6],
            ['academic_year_id' => 1, 'program_id' => 7, 'course' => 'Thêm Sức 2A',  'ordering' => 7],
            ['academic_year_id' => 1, 'program_id' => 7, 'course' => 'Thêm Sức 2B',  'ordering' => 8],
            ['academic_year_id' => 1, 'program_id' => 8, 'course' => 'Thêm Sức 3A',  'ordering' => 9],
            ['academic_year_id' => 1, 'program_id' => 8, 'course' => 'Thêm Sức 3B',  'ordering' => 10],
            ['academic_year_id' => 1, 'program_id' => 9, 'course' => 'Kinh Thánh 1',  'ordering' => 11],
            ['academic_year_id' => 1, 'program_id' => 10, 'course' => 'Kinh Thánh 2',  'ordering' => 12],
            ['academic_year_id' => 1, 'program_id' => 12, 'course' => 'Bao Đồng 1',  'ordering' => 13],
            ['academic_year_id' => 1, 'program_id' => 12, 'course' => 'Bao Đồng 2',  'ordering' => 14],

            ['academic_year_id' => 2, 'program_id' => 1, 'course' => 'Khai Tâm 1',  'ordering' => 1],
            ['academic_year_id' => 2, 'program_id' => 2, 'course' => 'Khai Tâm 2',  'ordering' => 2],
            ['academic_year_id' => 2, 'program_id' => 3, 'course' => 'Khai Tâm 3',  'ordering' => 3],
            ['academic_year_id' => 2, 'program_id' => 4, 'course' => 'Xưng Tội 1',  'ordering' => 4],
            ['academic_year_id' => 2, 'program_id' => 5, 'course' => 'Xưng Tội 2',   'ordering' => 5],
            ['academic_year_id' => 2, 'program_id' => 6, 'course' => 'Thêm Sức 1',  'ordering' => 6],
            ['academic_year_id' => 2, 'program_id' => 7, 'course' => 'Thêm Sức 2A',  'ordering' => 7],
            ['academic_year_id' => 2, 'program_id' => 7, 'course' => 'Thêm Sức 2B',  'ordering' => 8],
            ['academic_year_id' => 2, 'program_id' => 8, 'course' => 'Thêm Sức 3A',  'ordering' => 9],
            ['academic_year_id' => 2, 'program_id' => 8, 'course' => 'Thêm Sức 3B',  'ordering' => 10],
            ['academic_year_id' => 2, 'program_id' => 9, 'course' => 'Kinh Thánh 1',  'ordering' => 11],
            ['academic_year_id' => 2, 'program_id' => 10, 'course' => 'Kinh Thánh 2',  'ordering' => 12],
            ['academic_year_id' => 2, 'program_id' => 12, 'course' => 'Bao Đồng 1',  'ordering' => 13],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
