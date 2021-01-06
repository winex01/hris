<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EducationalLevelsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('educational_levels')->delete();
        
        \DB::table('educational_levels')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Elementary',
                'created_at' => '2021-01-07 05:27:50',
                'updated_at' => '2021-01-07 05:27:50',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'High School',
                'created_at' => '2021-01-07 05:28:05',
                'updated_at' => '2021-01-07 05:28:05',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Vocational School',
                'created_at' => '2021-01-07 05:28:11',
                'updated_at' => '2021-01-07 05:28:11',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Tertiary / College',
                'created_at' => '2021-01-07 05:28:22',
                'updated_at' => '2021-01-07 05:28:22',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Masters / Doctorate',
                'created_at' => '2021-01-07 05:28:33',
                'updated_at' => '2021-01-07 05:28:33',
            ),
        ));
        
        
    }
}