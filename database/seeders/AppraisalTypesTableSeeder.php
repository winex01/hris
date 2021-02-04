<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppraisalTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('appraisal_types')->delete();
        
        \DB::table('appraisal_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Annual Review',
                'created_at' => '2021-01-29 03:29:04',
                'updated_at' => '2021-01-29 03:29:04',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Probationary',
                'created_at' => '2021-01-29 03:29:21',
                'updated_at' => '2021-01-29 03:29:21',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Promotion',
                'created_at' => '2021-01-29 03:29:25',
                'updated_at' => '2021-01-29 03:29:25',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Quarterly Review',
                'created_at' => '2021-01-29 03:29:30',
                'updated_at' => '2021-01-29 03:29:30',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Others',
                'created_at' => '2021-01-29 03:29:36',
                'updated_at' => '2021-01-29 03:29:36',
            ),
        ));
        
        
    }
}