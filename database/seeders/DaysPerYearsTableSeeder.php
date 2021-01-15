<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DaysPerYearsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('days_per_years')->delete();
        
        \DB::table('days_per_years')->insert(array (
            0 => 
            array (
                'created_at' => '2021-01-16 02:35:34',
                'days_per_week' => 5.0,
                'days_per_year' => 261.0,
                'hours_per_day' => 8.0,
                'id' => 1,
                'updated_at' => '2021-01-16 02:50:45',
            ),
            1 => 
            array (
                'created_at' => '2021-01-16 02:55:21',
                'days_per_week' => 5.0,
                'days_per_year' => 312.0,
                'hours_per_day' => 8.0,
                'id' => 2,
                'updated_at' => '2021-01-16 02:55:21',
            ),
            2 => 
            array (
                'created_at' => '2021-01-16 02:55:33',
                'days_per_week' => 5.0,
                'days_per_year' => 313.0,
                'hours_per_day' => 8.0,
                'id' => 3,
                'updated_at' => '2021-01-16 02:55:33',
            ),
            3 => 
            array (
                'created_at' => '2021-01-16 02:55:39',
                'days_per_week' => 6.0,
                'days_per_year' => 313.0,
                'hours_per_day' => 8.0,
                'id' => 4,
                'updated_at' => '2021-01-16 02:55:39',
            ),
            4 => 
            array (
                'created_at' => '2021-01-16 02:55:47',
                'days_per_week' => 6.0,
                'days_per_year' => 314.0,
                'hours_per_day' => 8.0,
                'id' => 5,
                'updated_at' => '2021-01-16 02:55:47',
            ),
            5 => 
            array (
                'created_at' => '2021-01-16 02:55:55',
                'days_per_week' => 5.0,
                'days_per_year' => 360.0,
                'hours_per_day' => 8.0,
                'id' => 6,
                'updated_at' => '2021-01-16 02:55:55',
            ),
            6 => 
            array (
                'created_at' => '2021-01-16 02:56:03',
                'days_per_week' => 7.0,
                'days_per_year' => 365.0,
                'hours_per_day' => 8.0,
                'id' => 7,
                'updated_at' => '2021-01-16 02:56:03',
            ),
        ));
        
        
    }
}