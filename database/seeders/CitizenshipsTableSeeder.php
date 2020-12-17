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
            0 => 
            array (
                'created_at' => '2020-12-17 04:01:53',
                'id' => 1,
                'name' => 'Filipino',
                'updated_at' => '2020-12-17 04:01:53',
            ),
            1 => 
            array (
                'created_at' => '2020-12-17 04:01:58',
                'id' => 2,
                'name' => 'Chinese',
                'updated_at' => '2020-12-17 04:01:58',
            ),
            2 => 
            array (
                'created_at' => '2020-12-17 04:02:03',
                'id' => 3,
                'name' => 'Indian',
                'updated_at' => '2020-12-17 04:02:03',
            ),
            3 => 
            array (
                'created_at' => '2020-12-17 04:02:06',
                'id' => 4,
                'name' => 'Korean',
                'updated_at' => '2020-12-17 04:02:06',
            ),
            4 => 
            array (
                'created_at' => '2020-12-17 04:02:11',
                'id' => 5,
                'name' => 'American',
                'updated_at' => '2020-12-17 04:02:11',
            ),
            5 => 
            array (
                'created_at' => '2020-12-17 04:02:20',
                'id' => 6,
                'name' => 'Japanese',
                'updated_at' => '2020-12-17 04:02:20',
            ),
        ));
        
        
    }
}