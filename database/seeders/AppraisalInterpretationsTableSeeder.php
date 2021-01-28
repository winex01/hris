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
                'id' => 1,
                'name' => 'Exceptional Performance',
                'rating_from' => 95.0,
                'rating_to' => 100.0,
                'created_at' => '2021-01-29 03:22:06',
                'updated_at' => '2021-01-29 03:22:06',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Above Expectations',
                'rating_from' => 89.0,
                'rating_to' => 94.0,
                'created_at' => '2021-01-29 03:22:27',
                'updated_at' => '2021-01-29 03:22:27',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Meet Expectations',
                'rating_from' => 83.0,
                'rating_to' => 88.0,
                'created_at' => '2021-01-29 03:23:04',
                'updated_at' => '2021-01-29 03:24:29',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Below Expectations',
                'rating_from' => 77.0,
                'rating_to' => 82.0,
                'created_at' => '2021-01-29 03:25:11',
                'updated_at' => '2021-01-29 03:25:11',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Unacceptable Performance',
                'rating_from' => 0.0,
                'rating_to' => 76.0,
                'created_at' => '2021-01-29 03:25:32',
                'updated_at' => '2021-01-29 03:30:50',
            ),
        ));
        
        
    }
}