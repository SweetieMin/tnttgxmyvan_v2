<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GeneralSetting::create([
            'site_title' => 'TNTT Mỹ Vân',
            'site_email' => 'tnttgxmyvan@gmail.com',
            'site_phone' => '033 328 2774',
            'site_meta_keywords' => 'Đoàn TNTT giáo xứ Mỹ Vân',
            'site_meta_description' => 'Website Đoàn TNTT giáo xứ Mỹ Vân, xứ đoàn Giuse Đặng Đình Viên',
            'facebook_url' => 'https://www.facebook.com/profile.php?id=100069752143507#',
            'youtube_url' => 'https://www.youtube.com/@tnttgiaoxumyvan',
            'tikTok_url' => 'https://www.tiktok.com/@xudoangiusevien',
            'site_logo' => 'LOGO_default.png',
            'site_favicon' => 'FAVICON_default.png',
        ]);
    }
}
