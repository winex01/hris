<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppraisalInterpretationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('appraisal_interpretations')->delete();
        
        \DB::table('appraisal_interpretations')->insert(array (
            0 => 
            array (
                'created_at' => '2021-01-29 03:22:06',
                'id' => 1,
                'name' => 'Exceptional Performance',
                'rating_from' => 95.0,
                'rating_to' => 100.0,
                'updated_at' => '2021-01-29 03:22:06',
            ),
            1 => 
            array (
                'created_at' => '2021-01-29 03:22:27',
                'id' => 2,
                'name' => 'Above Expectations',
                'rating_from' => 89.0,
                'rating_to' => 94.99,
                'updated_at' => '2021-01-31 17:48:18',
            ),
            2 => 
            array (
                'created_at' => '2021-01-29 03:23:04',
                'id' => 3,
                'name' => 'Meet Expectations',
                'rating_from' => 83.0,
                'rating_to' => 88.99,
                'updated_at' => '2021-01-31 17:48:31',
            ),
            3 => 
            array (
                'created_at' => '2021-01-29 03:25:11',
                'id' => 4,
                'name' => 'Below Expectations',
                'rating_from' => 77.0,
                'rating_to' => 82.99,
                'updated_at' => '2021-01-31 17:48:41',
            ),
            4 => 
            array (
                'created_at' => '2021-01-29 03:25:32',
                'id' => 5,
                'name' => 'Unacceptable Performance',
                'rating_from' => 0.0,
                'rating_to' => 76.99,
                'updated_at' => '2021-01-31 17:48:51',
            ),
        ));
        
        
    }
}