<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class JobStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('job_statuses')->delete();
        
        \DB::table('job_statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Active',
                'created_at' => '2021-01-12 05:50:23',
                'updated_at' => '2021-01-12 05:50:23',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'End Of Contract',
                'created_at' => '2021-01-12 05:50:35',
                'updated_at' => '2021-01-12 05:50:35',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Forced Leave',
                'created_at' => '2021-01-12 05:50:42',
                'updated_at' => '2021-01-12 05:50:42',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Resigned',
                'created_at' => '2021-01-12 05:50:51',
                'updated_at' => '2021-01-12 05:50:51',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Retired',
                'created_at' => '2021-01-12 05:50:55',
                'updated_at' => '2021-01-12 05:50:55',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Terminated',
                'created_at' => '2021-01-12 05:51:01',
                'updated_at' => '2021-01-12 05:51:01',
            ),
        ));
        
        
    }
}