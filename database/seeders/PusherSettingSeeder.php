<?php

namespace Database\Seeders;

use App\Models\PusherSetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PusherSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PusherSetting::create([
            'app_id' => '2000316',
            'key' => '6cee5aae3038a7058a99',
            'secret' => 'f8d5f5ff9287643d68ef',
            'cluster' => 'ap1',
            'port' => 443,
            'scheme' => 'https',
        ]);
    }
}
