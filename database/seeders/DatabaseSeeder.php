<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            ProgramSeeder::class,
            CourseSeeder::class,
            SectorSeeder::class,
            RoleSeeder::class,
            RoleHierarchySeeder::class,
            RegulationSeeder::class,
            TransactionItemSeeder::class,
        ]);


        // Gán quyền
        DB::table('role_user')->insert([
            ['user_id' => 1, 'role_id' => 1], // admin
        ]);
        

    }
}
