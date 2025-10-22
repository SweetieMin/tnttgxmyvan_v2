<?php

namespace Database\Seeders;

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
                'name' => '2024-2025',
                'catechism_start_date' => '2024-09-01',
                'catechism_end_date'=> '2025-06-30',
                'catechism_avg_score' => 5,
                'catechism_training_score'=> 5,
                'activity_start_date'=> '2024-09-01',
                'activity_end_date' => '2025-07-31',
                'activity_score' =>  200,
                'status_academic' => 'finished',
            ],
            [
                'name' => '2025-2026',
                'catechism_start_date' => '2025-09-01',
                'catechism_end_date'=> '2026-06-30',
                'catechism_avg_score' => 5,
                'catechism_training_score'=> 5,
                'activity_start_date'=> '2025-09-01',
                'activity_end_date' => '2026-07-31',
                'activity_score' =>  200,
                'status_academic' => 'ongoing',
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
                'name' => '2027-2028',
                'catechism_start_date' => '2027-09-01',
                'catechism_end_date'=> '2028-06-30',
                'catechism_avg_score' => 5,
                'catechism_training_score'=> 5,
                'activity_start_date'=> '2027-09-01',
                'activity_end_date' => '2028-07-31',
                'activity_score' =>  200,
                'status_academic' => 'upcoming',
            ],
            [
                'name' => '2028-2029',
                'catechism_start_date' => '2028-09-01',
                'catechism_end_date'=> '2029-06-30',
                'catechism_avg_score' => 5,
                'catechism_training_score'=> 5,
                'activity_start_date'=> '2028-09-01',
                'activity_end_date' => '2029-07-31',
                'activity_score' =>  200,
                'status_academic' => 'upcoming',
            ],
        ];

        foreach ($years as $year){
            app(AcademicYearRepositoryInterface::class)->create($year);
        }
    }
}
