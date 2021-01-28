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
                'name' => 'Quarterly Review',
                'created_at' => '2021-01-29 02:59:21',
                'updated_at' => '2021-01-29 02:59:21',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Annual Review',
                'created_at' => '2021-01-29 02:59:34',
                'updated_at' => '2021-01-29 02:59:34',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Promotion',
                'created_at' => '2021-01-29 02:59:41',
                'updated_at' => '2021-01-29 02:59:41',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Probationary',
                'created_at' => '2021-01-29 02:59:47',
                'updated_at' => '2021-01-29 02:59:47',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Others',
                'created_at' => '2021-01-29 02:59:52',
                'updated_at' => '2021-01-29 02:59:52',
            ),
        ));
        
        
    }
}