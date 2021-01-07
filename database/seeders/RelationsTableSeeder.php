<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RelationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('relations')->delete();
        
        \DB::table('relations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Child',
                'created_at' => '2021-01-07 21:19:29',
                'updated_at' => '2021-01-07 21:19:29',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Father',
                'created_at' => '2021-01-07 21:19:39',
                'updated_at' => '2021-01-07 21:19:39',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Mother',
                'created_at' => '2021-01-07 21:19:43',
                'updated_at' => '2021-01-07 21:19:43',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Spouse',
                'created_at' => '2021-01-07 21:19:47',
                'updated_at' => '2021-01-07 21:19:47',
            ),
        ));
        
        
    }
}