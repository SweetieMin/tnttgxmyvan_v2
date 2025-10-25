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
            ['academic_year_id' => 1, 'program_id' =>  1 , 'sector' => 'Nghĩa Sĩ',  'ordering' => 1],
            ['academic_year_id' => 1, 'program_id' =>  2 , 'sector' => 'Thiếu 3',  'ordering' => 2],
            ['academic_year_id' => 1, 'program_id' =>  3 , 'sector' => 'Thiếu 2',  'ordering' => 3],
            ['academic_year_id' => 1, 'program_id' =>  4 , 'sector' => 'Thiếu 1',  'ordering' => 4],
            ['academic_year_id' => 1, 'program_id' =>  5 , 'sector' => 'Ấu 3',  'ordering' => 5],
            ['academic_year_id' => 1, 'program_id' =>  6 , 'sector' => 'Ấu 2',  'ordering' => 6],
            ['academic_year_id' => 1, 'program_id' =>  7 , 'sector' => 'Ấu 1',  'ordering' => 7],
            ['academic_year_id' => 1, 'program_id' =>  8 , 'sector' => 'Tiền Ấu',  'ordering' => 8],

            ['academic_year_id' => 2, 'program_id' =>  1 , 'sector' => 'Nghĩa Sĩ 3',  'ordering' => 1],
            ['academic_year_id' => 2, 'program_id' =>  2 , 'sector' => 'Nghĩa Sĩ 2',  'ordering' => 2],
            ['academic_year_id' => 2, 'program_id' =>  3 , 'sector' => 'Nghĩa Sĩ 1', 'ordering' => 3],
            ['academic_year_id' => 2, 'program_id' =>  4 , 'sector' => 'Thiếu 3',  'ordering' => 4],
            ['academic_year_id' => 2, 'program_id' =>  5 , 'sector' => 'Thiếu 2',  'ordering' => 5],
            ['academic_year_id' => 2, 'program_id' =>  6 , 'sector' => 'Thiếu 1',  'ordering' => 6],
            ['academic_year_id' => 2, 'program_id' =>  7 , 'sector' => 'Ấu 3',  'ordering' => 7],
            ['academic_year_id' => 2, 'program_id' =>  8 , 'sector' => 'Ấu 2',  'ordering' => 8],
            ['academic_year_id' => 2, 'program_id' =>  9 , 'sector' => 'Ấu 1',  'ordering' => 9],
            ['academic_year_id' => 2, 'program_id' =>  10 , 'sector' => 'Tiền Ấu',  'ordering' => 10],
        ];


        foreach ($sectors as $sector) {
            Sector::create($sector);
        }
    }
}
