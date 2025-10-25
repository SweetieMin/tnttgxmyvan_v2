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
            ['academic_year_id' => 1, 'ordering' => 1 , 'program_id' => 1,  'course' => 'Khai Tâm 1'],
            ['academic_year_id' => 1, 'ordering' => 2 , 'program_id' => 2,  'course' => 'Khai Tâm 2'],
            ['academic_year_id' => 1, 'ordering' => 3 , 'program_id' => 3,  'course' => 'Khai Tâm 3'],
            ['academic_year_id' => 1, 'ordering' => 4 , 'program_id' => 4,  'course' => 'Xưng Tội 1'],
            ['academic_year_id' => 1, 'ordering' => 5 , 'program_id' => 5,  'course' => 'Xưng Tội 2'],
            ['academic_year_id' => 1, 'ordering' => 6 , 'program_id' => 6,  'course' => 'Thêm Sức 1'],
            ['academic_year_id' => 1, 'ordering' => 7 , 'program_id' => 7,  'course' => 'Thêm Sức 2A'],
            ['academic_year_id' => 1, 'ordering' => 8 , 'program_id' => 7,  'course' => 'Thêm Sức 2B'],
            ['academic_year_id' => 1, 'ordering' => 9 , 'program_id' => 8,  'course' => 'Thêm Sức 3A'],
            ['academic_year_id' => 1, 'ordering' => 10 , 'program_id' => 8,  'course' => 'Thêm Sức 3B'],
            ['academic_year_id' => 1, 'ordering' =>  11, 'program_id' => 9,  'course' => 'Kinh Thánh 1'],
            ['academic_year_id' => 1, 'ordering' => 12 , 'program_id' => 10, 'course' => 'Kinh Thánh 2'],
            ['academic_year_id' => 1, 'ordering' => 13 , 'program_id' => 12, 'course' => 'Vào Đời'],

            ['academic_year_id' => 2,  'ordering' => 1, 'program_id' => 1,  'course' => 'Khai Tâm 1'],
            ['academic_year_id' => 2,  'ordering' => 2, 'program_id' => 2,  'course' => 'Khai Tâm 2'],
            ['academic_year_id' => 2,  'ordering' => 3, 'program_id' => 3,  'course' => 'Khai Tâm 3'],
            ['academic_year_id' => 2,  'ordering' => 4, 'program_id' => 4,  'course' => 'Xưng Tội 1'],
            ['academic_year_id' => 2,  'ordering' => 5, 'program_id' => 5,  'course' => 'Xưng Tội 2'],
            ['academic_year_id' => 2,  'ordering' => 6, 'program_id' => 6,  'course' => 'Thêm Sức 1'],
            ['academic_year_id' => 2,  'ordering' => 7, 'program_id' => 7,  'course' => 'Thêm Sức 2'],
            ['academic_year_id' => 2,  'ordering' => 8, 'program_id' => 8,  'course' => 'Thêm Sức 3A'],
            ['academic_year_id' => 2,  'ordering' => 9, 'program_id' => 8,  'course' => 'Thêm Sức 3B'],
            ['academic_year_id' => 2,  'ordering' => 10, 'program_id' => 9,  'course' => 'Kinh Thánh 1A'],
            ['academic_year_id' => 2,  'ordering' => 11, 'program_id' => 9,  'course' => 'Kinh Thánh 1B'],
            ['academic_year_id' => 2,  'ordering' => 12, 'program_id' => 10, 'course' => 'Kinh Thánh 2'],
            ['academic_year_id' => 2,  'ordering' => 13, 'program_id' => 12, 'course' => 'Vào Đời'],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
