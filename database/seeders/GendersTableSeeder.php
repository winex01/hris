<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GendersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('genders')->delete();
        
        \DB::table('genders')->insert(array (
            0 => 
            array (
                'created_at' => '2020-12-17 03:56:48',
                'id' => 1,
                'name' => 'Male',
                'updated_at' => '2020-12-17 03:56:48',
            ),
            1 => 
            array (
                'created_at' => '2020-12-17 03:56:58',
                'id' => 2,
                'name' => 'Female',
                'updated_at' => '2020-12-17 03:56:58',
            ),
        ));
        
        
    }
}