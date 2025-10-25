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
            ['academic_year_id' => 1, 'ordering' => 1, 'program_id' => 1,  'sector' => 'Tiền Ấu 1'],
            ['academic_year_id' => 1, 'ordering' => 2, 'program_id' => 2,  'sector' => 'Tiền Ấu 2'],
            ['academic_year_id' => 1, 'ordering' => 3, 'program_id' => 3,  'sector' => 'Tiền Ấu 3'],
            ['academic_year_id' => 1, 'ordering' => 4, 'program_id' => 4,  'sector' => 'Ấu 1'],
            ['academic_year_id' => 1, 'ordering' => 5, 'program_id' => 5,  'sector' => 'Ấu 2'],
            ['academic_year_id' => 1, 'ordering' => 6, 'program_id' => 6,  'sector' => 'Ấu 3'],
            ['academic_year_id' => 1, 'ordering' => 7, 'program_id' => 7,  'sector' => 'Thiếu 1A'],
            ['academic_year_id' => 1, 'ordering' => 8, 'program_id' => 7,  'sector' => 'Thiếu 1B'],
            ['academic_year_id' => 1, 'ordering' => 9, 'program_id' => 8,  'sector' => 'Thiếu 2A'],
            ['academic_year_id' => 1, 'ordering' => 10, 'program_id' => 8,  'sector' => 'Thiếu 2B'],
            ['academic_year_id' => 1, 'ordering' => 11, 'program_id' => 9,  'sector' => 'Thiếu 3'],
            ['academic_year_id' => 1, 'ordering' => 12, 'program_id' => 10, 'sector' => 'Nghĩa 1'],
            ['academic_year_id' => 1, 'ordering' => 13, 'program_id' => 12, 'sector' => 'Nghĩa 3'],

            ['academic_year_id' => 2, 'ordering' => 1, 'program_id' => 1,  'sector' => 'Tiền Ấu 1'],
            ['academic_year_id' => 2, 'ordering' => 2, 'program_id' => 2,  'sector' => 'Tiền Ấu 2'],
            ['academic_year_id' => 2, 'ordering' => 3, 'program_id' => 3,  'sector' => 'Tiền Ấu 3'],
            ['academic_year_id' => 2, 'ordering' => 4, 'program_id' => 4,  'sector' => 'Ấu 1'],
            ['academic_year_id' => 2, 'ordering' => 5, 'program_id' => 5,  'sector' => 'Ấu 2'],
            ['academic_year_id' => 2, 'ordering' => 6, 'program_id' => 6,  'sector' => 'Ấu 3'],
            ['academic_year_id' => 2, 'ordering' => 7, 'program_id' => 7,  'sector' => 'Thiếu 1'],
            ['academic_year_id' => 2, 'ordering' => 8, 'program_id' => 8,  'sector' => 'Thiếu 2A'],
            ['academic_year_id' => 2, 'ordering' => 9, 'program_id' => 8,  'sector' => 'Thiếu 2B'],
            ['academic_year_id' => 2, 'ordering' => 10, 'program_id' => 9,  'sector' => 'Thiếu 3A'],
            ['academic_year_id' => 2, 'ordering' => 11, 'program_id' => 9,  'sector' => 'Thiếu 3B'],
            ['academic_year_id' => 2, 'ordering' => 12, 'program_id' => 10, 'sector' => 'Nghĩa 1'],
            ['academic_year_id' => 2, 'ordering' => 13, 'program_id' => 12, 'sector' => 'Nghĩa 3'],
        ];


        foreach ($sectors as $sector) {
            Sector::create($sector);
        }
    }
}
