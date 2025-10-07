<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'christian_name' => 'Giuse',
                'last_name'=> 'Đặng Đình',
                'name'=> 'Viên',
                'birthday'=> '2010-08-21',
                'email' => 'tntt.myvan@gmail.com',
                'password'=> '12345',
            ],
            [
                'christian_name' => 'Toma',
                'last_name'=> 'Nguyễn Khắc',
                'name'=> 'Huấn',
                'birthday'=> '1997-01-19',
                'email'=> 'nguyenkhachuan1997@gmail.com',
                'password'=> '12345',
            ],
        ];

        foreach ($users as $user){
            User::create($user);
        }
    }
}
