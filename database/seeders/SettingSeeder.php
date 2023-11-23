<?php

namespace Database\Seeders;

use App\Enums\SettingEnum;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(SettingEnum::DEFAULT_SETTINGS);
    }
}
