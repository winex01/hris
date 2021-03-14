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
                'created_at' => '2021-03-14 08:45:01',
                'id' => 1,
                'name' => 'Regular Holiday',
                'updated_at' => '2021-03-14 08:45:01',
            ),
            1 => 
            array (
                'created_at' => '2021-03-14 08:45:01',
                'id' => 2,
                'name' => 'Special Holiday',
                'updated_at' => '2021-03-14 08:45:01',
            ),
        ));
        
        
    }
}