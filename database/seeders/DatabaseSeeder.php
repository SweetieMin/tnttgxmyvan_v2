<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            UserDetailSeeder::class,
            AcademicYearSeeder::class,
            CourseSeeder::class,
            SectorSeeder::class,
            RoleSeeder::class,
            RoleHierarchySeeder::class,
            RegulationSeeder::class,
        ]);
    }
}
