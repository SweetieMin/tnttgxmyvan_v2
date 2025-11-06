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
                'account_code' => 'MV21081010',
                'email' => 'tntt.myvan@gmail.com',
                'password'=> '12345',
            ],
            [
                'christian_name' => 'Toma',
                'last_name'=> 'Nguyễn Khắc',
                'name'=> 'Huấn',
                'account_code' => 'MV19019797',
                'birthday'=> '1997-01-19',
                'email'=> 'nguyenkhachuan1997@gmail.com',
                'password'=> '12345',
            ],
            [
                'christian_name' => 'Toma',
                'last_name'=> 'Vũ Minh',
                'name'=> 'Đức',
                'account_code' => 'MV01109999',
                'birthday'=> '1999-10-01',
                'password'=> '12345',
            ],
            [
                'christian_name' => 'Teresa',
                'last_name'=> 'Nguyễn Thị Thúy',
                'name'=> 'Vy',
                'account_code' => 'MV11100101',
                'birthday'=> '2001-10-11',
                'password'=> '12345',
            ],
            [
                'christian_name' => 'Teresa',
                'last_name'=> 'Nguyễn Thị Ngọc',
                'name'=> 'Vân',
                'account_code' => 'MV19089999',
                'birthday'=> '1999-08-19',
                'password'=> '12345',
            ],
            [
                'christian_name' => 'Maria',
                'last_name'=> 'Nguyễn Thị Bích',
                'name'=> 'Liên',
                'account_code' => 'MV22109686',
                'birthday'=> '1996-10-22',
                'password'=> '12345',
            ],
            [
                'christian_name' => 'Maria',
                'last_name'=> 'Đoàn Trường',
                'name'=> 'Nam',
                'account_code' => 'MV21099898',
                'birthday'=> '1998-09-21',
                'password'=> '12345',
            ],
            [
                'christian_name' => 'Maria Monica',
                'last_name'=> 'Nguyễn Thị Kim',
                'name'=> 'Anh',
                'account_code' => 'MV26089924',
                'birthday'=> '1999-08-26',
                'password'=> '12345',
            ],
            [
                'christian_name' => 'Maria',
                'last_name'=> 'Vũ Hồng',
                'name'=> 'Phúc',
                'account_code' => 'MV03010574',
                'birthday'=> '2005-01-03',
                'password'=> '12345',
            ],
            [
                'christian_name' => 'Monica',
                'last_name'=> 'Nguyễn Hoàng Kim',
                'name'=> 'Dung',
                'account_code' => 'MV26030101',
                'birthday'=> '2001-03-26',
                'password'=> '12345',
            ],
            [
                'christian_name' => 'Toma',
                'last_name'=> 'Vũ Tấn',
                'name'=> 'Lộc',
                'account_code' => 'MV01070274',
                'birthday'=> '2002-07-01',
                'password'=> '12345',
            ],
            [
                'christian_name' => 'Maria',
                'last_name'=> 'Phạm Ngọc',
                'name'=> 'Quỳnh',
                'account_code' => 'MV14090524',
                'birthday'=> '2005-09-14',
                'password'=> '12345',
            ],
        ];

        foreach ($users as $user){
            User::create($user);
        }
    }
}
