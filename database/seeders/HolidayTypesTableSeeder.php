<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class HolidayTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('holiday_types')->delete();
        
        \DB::table('holiday_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Regular Holiday',
                'created_at' => '2021-03-14 08:45:01',
                'updated_at' => '2021-03-14 08:45:01',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Special Holiday',
                'created_at' => '2021-03-14 08:45:01',
                'updated_at' => '2021-03-14 08:45:01',
            ),
            2 => 
            array (
                'id' => 3,
            'name' => 'Double Holiday (Regular + Special)',
                'created_at' => '2021-03-15 15:28:20',
                'updated_at' => '2021-03-15 15:28:20',
            ),
        ));
        
        
    }
}