<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CitizenshipsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('citizenships')->delete();
        
        \DB::table('citizenships')->insert(array (
            1 => 
            array (
                'id' => 1,
                'name' => 'Filipino',
                'created_at' => '2020-12-17 04:01:53',
                'updated_at' => '2020-12-17 04:01:53',
            ),
            2 => 
            array (
                'id' => 2,
                'name' => 'Chinese',
                'created_at' => '2020-12-17 04:01:58',
                'updated_at' => '2020-12-17 04:01:58',
            ),
            3 => 
            array (
                'id' => 3,
                'name' => 'Indian',
                'created_at' => '2020-12-17 04:02:03',
                'updated_at' => '2020-12-17 04:02:03',
            ),
            4 => 
            array (
                'id' => 4,
                'name' => 'Korean',
                'created_at' => '2020-12-17 04:02:06',
                'updated_at' => '2020-12-17 04:02:06',
            ),
            5 => 
            array (
                'id' => 5,
                'name' => 'American',
                'created_at' => '2020-12-17 04:02:11',
                'updated_at' => '2020-12-17 04:02:11',
            ),
            6 => 
            array (
                'id' => 6,
                'name' => 'Japanese',
                'created_at' => '2020-12-17 04:02:20',
                'updated_at' => '2020-12-17 04:02:20',
            ),
            7 => 
            array (
                'id' => 7,
                'name' => 'Australian',
                'created_at' => '2021-01-12 10:05:08',
                'updated_at' => '2021-01-12 10:05:08',
            ),
        ));
        
        
    }
}