<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReligionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('religions')->delete();
        
        \DB::table('religions')->insert(array (
            1 => 
            array (
                'created_at' => '2020-12-17 03:58:03',
                'id' => 1,
                'name' => 'Roman Catholic',
                'updated_at' => '2020-12-17 03:58:03',
            ),
            2 => 
            array (
                'created_at' => '2020-12-17 03:58:08',
                'id' => 2,
                'name' => 'Islam',
                'updated_at' => '2020-12-17 03:58:08',
            ),
            3 => 
            array (
                'created_at' => '2020-12-17 03:58:26',
                'id' => 3,
                'name' => 'Evangelicals',
                'updated_at' => '2020-12-17 03:58:26',
            ),
            4 => 
            array (
                'created_at' => '2020-12-17 03:58:30',
                'id' => 4,
                'name' => 'Iglesia ni Cristo',
                'updated_at' => '2020-12-17 03:58:30',
            ),
            5 => 
            array (
                'created_at' => '2020-12-17 03:58:39',
                'id' => 5,
                'name' => 'Protestant',
                'updated_at' => '2020-12-17 03:58:39',
            ),
            6 => 
            array (
                'created_at' => '2020-12-17 03:58:57',
                'id' => 6,
                'name' => 'Aglipayan',
                'updated_at' => '2020-12-17 03:58:57',
            ),
            7 => 
            array (
                'created_at' => '2020-12-17 03:59:03',
                'id' => 7,
                'name' => 'Seventh-day Adventist',
                'updated_at' => '2020-12-17 03:59:03',
            ),
            8 => 
            array (
                'created_at' => '2020-12-17 03:59:10',
                'id' => 8,
                'name' => 'Bible Baptist Church',
                'updated_at' => '2020-12-17 03:59:10',
            ),
            9 => 
            array (
                'created_at' => '2020-12-17 03:59:17',
                'id' => 9,
                'name' => 'United Church of Christ in the Philippines',
                'updated_at' => '2020-12-17 03:59:17',
            ),
            10 => 
            array (
                'created_at' => '2020-12-17 03:59:21',
                'id' => 10,
                'name' => 'Jehovah\'s Witnesses',
                'updated_at' => '2020-12-17 03:59:21',
            ),
        ));
        
        
    }
}