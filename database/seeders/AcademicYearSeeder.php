<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = [
            [
                'name' => '2025-2026',
                'catechism_start_date' => '2025-09-01',
                'catechism_end_date'=> '2026-06-30',
                'catechism_avg_score' => 5,
                'catechism_training_score'=> 5,
                'activity_start_date'=> '2025-09-01',
                'activity_end_date' => '2026-07-31',
                'activity_score' =>  200,
                'status_academic' => 'upcoming',
            ],
            [
                'name' => '2026-2027',
                'catechism_start_date' => '2026-09-01',
                'catechism_end_date'=> '2027-06-30',
                'catechism_avg_score' => 5,
                'catechism_training_score'=> 5,
                'activity_start_date'=> '2026-09-01',
                'activity_end_date' => '2027-07-31',
                'activity_score' =>  200,
                'status_academic' => 'upcoming',
            ],
            [
                'name' => '2025-2027',
                'catechism_start_date' => '2025-09-01',
                'catechism_end_date'=> '2026-06-30',
                'catechism_avg_score' => 5,
                'catechism_training_score'=> 5,
                'activity_start_date'=> '2025-09-01',
                'activity_end_date' => '2026-07-31',
                'activity_score' =>  200,
                'status_academic' => 'upcoming',
            ],
            [
                'name' => '2026-2028',
                'catechism_start_date' => '2026-09-01',
                'catechism_end_date'=> '2027-06-30',
                'catechism_avg_score' => 5,
                'catechism_training_score'=> 5,
                'activity_start_date'=> '2026-09-01',
                'activity_end_date' => '2027-07-31',
                'activity_score' =>  200,
                'status_academic' => 'upcoming',
            ],
        ];

        foreach ($years as $year){
            app(AcademicYearRepositoryInterface::class)->create($year);
        }
    }
}
