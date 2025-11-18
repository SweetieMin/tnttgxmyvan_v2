<?php

namespace Database\Seeders;

use App\Models\MailSetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MailSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MailSetting::create([
            'mailer' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'username' => 'tntt.myvan@gmail.com',
            'password' => 'zqgo ravx tniu ppky',
            'encryption' => 'tls',
            'from_address' => 'tntt.myvan@gmail.com',
            'from_name' => 'Đoàn TNTT giáo xứ Mỹ Vân',
        ]);
    }
}
