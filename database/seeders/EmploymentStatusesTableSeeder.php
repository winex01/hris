<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EmploymentStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('employment_statuses')->delete();
        
        \DB::table('employment_statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Contractual',
                'created_at' => '2021-01-12 07:12:40',
                'updated_at' => '2021-01-12 07:12:40',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Probationary',
                'created_at' => '2021-01-12 07:12:51',
                'updated_at' => '2021-01-12 07:12:51',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Regular',
                'created_at' => '2021-01-12 07:12:57',
                'updated_at' => '2021-01-12 07:12:57',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Special Project',
                'created_at' => '2021-01-12 07:13:07',
                'updated_at' => '2021-01-12 07:13:07',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Trainee / Intern',
                'created_at' => '2021-01-12 07:13:15',
                'updated_at' => '2021-01-12 07:13:15',
            ),
        ));
        
        
    }
}