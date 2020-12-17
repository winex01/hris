<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BloodTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('blood_types')->delete();
        
        \DB::table('blood_types')->insert(array (
            0 => 
            array (
                'created_at' => '2020-12-17 03:48:38',
                'id' => 1,
                'name' => 'AB-',
                'updated_at' => '2020-12-17 03:49:23',
            ),
            1 => 
            array (
                'created_at' => '2020-12-17 03:49:10',
                'id' => 2,
                'name' => 'AB+',
                'updated_at' => '2020-12-17 03:49:31',
            ),
            2 => 
            array (
                'created_at' => '2020-12-17 03:49:37',
                'id' => 3,
                'name' => 'O-',
                'updated_at' => '2020-12-17 03:49:37',
            ),
            3 => 
            array (
                'created_at' => '2020-12-17 03:49:47',
                'id' => 4,
                'name' => 'O+',
                'updated_at' => '2020-12-17 03:49:47',
            ),
            4 => 
            array (
                'created_at' => '2020-12-17 03:49:53',
                'id' => 5,
                'name' => 'B-',
                'updated_at' => '2020-12-17 03:49:53',
            ),
            5 => 
            array (
                'created_at' => '2020-12-17 03:50:00',
                'id' => 6,
                'name' => 'B+',
                'updated_at' => '2020-12-17 03:50:00',
            ),
            6 => 
            array (
                'created_at' => '2020-12-17 03:50:05',
                'id' => 7,
                'name' => 'A-',
                'updated_at' => '2020-12-17 03:50:05',
            ),
            7 => 
            array (
                'created_at' => '2020-12-17 03:50:10',
                'id' => 8,
                'name' => 'A+',
                'updated_at' => '2020-12-17 03:50:10',
            ),
        ));
        
        
    }
}