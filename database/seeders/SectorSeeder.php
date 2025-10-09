<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectors = [
            ['academic_year_id' => 1,'name' => 'Nghĩa Sĩ', 'description' => 'Dành cho các em từ 14 đến 16 tuổi', 'ordering' => 1],
            ['academic_year_id' => 1,'name' => 'Thiếu 3', 'description' => 'Dành cho các em 13 tuổi', 'ordering' => 2],
            ['academic_year_id' => 1,'name' => 'Thiếu 2', 'description' => 'Dành cho các em 12 tuổi', 'ordering' => 3],
            ['academic_year_id' => 1,'name' => 'Thiếu 1', 'description' => 'Dành cho các em 11 tuổi', 'ordering' => 4],
            ['academic_year_id' => 1,'name' => 'Ấu 3', 'description' => 'Dành cho các em 10 tuổi', 'ordering' => 5],
            ['academic_year_id' => 1,'name' => 'Ấu 2', 'description' => 'Dành cho các em 9 tuổi', 'ordering' => 6],
            ['academic_year_id' => 1,'name' => 'Ấu 1', 'description' => 'Dành cho các em 8 tuổi', 'ordering' => 7],
            ['academic_year_id' => 1,'name' => 'Tiền Ấu', 'description' => 'Dành cho các em từ 4 đến 7 tuổi', 'ordering' => 8],

            ['academic_year_id' => 2,'name' => 'Nghĩa Sĩ 3', 'description' => 'Dành cho các em từ 16 tuổi', 'ordering' => 1],
            ['academic_year_id' => 2,'name' => 'Nghĩa Sĩ 2', 'description' => 'Dành cho các em từ 15 tuổi', 'ordering' => 2],
            ['academic_year_id' => 2,'name' => 'Nghĩa Sĩ 1', 'description' => 'Dành cho các em từ 14 tuổi', 'ordering' => 3],
            ['academic_year_id' => 2,'name' => 'Thiếu 3', 'description' => 'Dành cho các em 13 tuổi', 'ordering' => 4],
            ['academic_year_id' => 2,'name' => 'Thiếu 2', 'description' => 'Dành cho các em 12 tuổi', 'ordering' => 5],
            ['academic_year_id' => 2,'name' => 'Thiếu 1', 'description' => 'Dành cho các em 11 tuổi', 'ordering' => 6],
            ['academic_year_id' => 2,'name' => 'Ấu 3', 'description' => 'Dành cho các em 10 tuổi', 'ordering' => 7],
            ['academic_year_id' => 2,'name' => 'Ấu 2', 'description' => 'Dành cho các em 9 tuổi', 'ordering' => 8],
            ['academic_year_id' => 2,'name' => 'Ấu 1', 'description' => 'Dành cho các em 8 tuổi', 'ordering' => 9],
            ['academic_year_id' => 2,'name' => 'Tiền Ấu', 'description' => 'Dành cho các em từ 4 đến 7 tuổi', 'ordering' => 10],
        ];


        foreach ($sectors as $sector) {
            Sector::create($sector);
        }
    }
}
