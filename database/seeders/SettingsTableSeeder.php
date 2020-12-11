<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * The settings to add.
     */
    protected $settings = [
        [
            'key'         => 'debugbar_enabled',
            'name'        => 'debugbar.enabled',
            'description' => 'Laravel debugbar package.',
            'value'       => false,
            'field'       => '{"name":"value","label":"Enabled","type":"boolean"}',
            'active'      => 1,
        ],
        [
            'key'         => 'hris_log_query',
            'name'        => 'hris.log_query',
            'description' => 'Log query in laravel.log file.',
            'value'       => false,
            'field'       => '{"name":"value","label":"Enabled","type":"boolean"}',
            'active'      => 1,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();

        foreach ($this->settings as $index => $setting) {
            $result = DB::table('settings')->insert($setting);

            if (!$result) {
                $this->command->info("Insert failed at record $index.");

                return;
            }
        }

        $this->command->info('Inserted '.count($this->settings).' records.');
    }
}
