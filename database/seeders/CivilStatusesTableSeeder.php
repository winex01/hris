<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CivilStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('civil_statuses')->delete();
        
        \DB::table('civil_statuses')->insert(array (
            0 => 
            array (
                'created_at' => '2020-12-17 03:54:17',
                'id' => 1,
                'name' => '-',
                'updated_at' => '2020-12-17 03:54:17',
            ),
            1 => 
            array (
                'created_at' => '2020-12-17 03:54:17',
                'id' => 2,
                'name' => 'Married',
                'updated_at' => '2020-12-17 03:54:17',
            ),
            2 => 
            array (
                'created_at' => '2020-12-17 03:54:26',
                'id' => 3,
                'name' => 'Widowed',
                'updated_at' => '2020-12-17 03:54:26',
            ),
            3 => 
            array (
                'created_at' => '2020-12-17 03:54:40',
                'id' => 4,
                'name' => 'Separated',
                'updated_at' => '2020-12-17 03:54:40',
            ),
            4 => 
            array (
                'created_at' => '2020-12-17 03:54:50',
                'id' => 5,
                'name' => 'Divorced',
                'updated_at' => '2020-12-17 03:54:50',
            ),
            5 => 
            array (
                'created_at' => '2020-12-17 03:54:58',
                'id' => 6,
                'name' => 'Single',
                'updated_at' => '2020-12-17 03:54:58',
            ),
        ));
        
        
    }
}