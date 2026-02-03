<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\AppSettings;
use Illuminate\Database\Seeder;

class AppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_settings = [
            'version' => '2.0.1',
        ];
        AppSettings::firstOrCreate(
            ['version' => $app_settings['version']],
            $app_settings
        );
    }
}
