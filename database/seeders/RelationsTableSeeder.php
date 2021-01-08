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
                'created_at' => '2021-01-07 21:19:29',
                'id' => 1,
                'name' => 'Child',
                'updated_at' => '2021-01-07 21:19:29',
            ),
            1 => 
            array (
                'created_at' => '2021-01-07 21:19:39',
                'id' => 2,
                'name' => 'Father',
                'updated_at' => '2021-01-07 21:19:39',
            ),
            2 => 
            array (
                'created_at' => '2021-01-07 21:19:43',
                'id' => 3,
                'name' => 'Mother',
                'updated_at' => '2021-01-07 21:19:43',
            ),
            3 => 
            array (
                'created_at' => '2021-01-07 21:19:47',
                'id' => 4,
                'name' => 'Spouse',
                'updated_at' => '2021-01-07 21:19:47',
            ),
            4 => 
            array (
                'created_at' => '2021-01-08 22:12:07',
                'id' => 5,
                'name' => 'Emergency Contact',
                'updated_at' => '2021-01-08 22:12:07',
            ),
            5 => 
            array (
                'created_at' => '2021-01-08 22:12:19',
                'id' => 6,
                'name' => 'Character Reference',
                'updated_at' => '2021-01-08 22:12:19',
            ),
            6 => 
            array (
                'created_at' => '2021-01-08 22:42:07',
                'id' => 7,
                'name' => 'Aunt',
                'updated_at' => '2021-01-08 22:42:07',
            ),
            7 => 
            array (
                'created_at' => '2021-01-08 22:42:14',
                'id' => 8,
                'name' => 'Uncle',
                'updated_at' => '2021-01-08 22:42:14',
            ),
            8 => 
            array (
                'created_at' => '2021-01-08 22:42:20',
                'id' => 9,
                'name' => 'Cousin',
                'updated_at' => '2021-01-08 22:42:20',
            ),
            9 => 
            array (
                'created_at' => '2021-01-08 22:42:29',
                'id' => 10,
                'name' => 'Relative',
                'updated_at' => '2021-01-08 22:42:29',
            ),
            10 => 
            array (
                'created_at' => '2021-01-08 22:42:41',
                'id' => 11,
                'name' => 'Nephew',
                'updated_at' => '2021-01-08 22:42:41',
            ),
            11 => 
            array (
                'created_at' => '2021-01-08 22:42:53',
                'id' => 12,
                'name' => 'Niece',
                'updated_at' => '2021-01-08 22:42:53',
            ),
            12 => 
            array (
                'created_at' => '2021-01-08 22:43:26',
                'id' => 13,
                'name' => 'Brother',
                'updated_at' => '2021-01-08 22:43:26',
            ),
            13 => 
            array (
                'created_at' => '2021-01-08 22:43:31',
                'id' => 14,
                'name' => 'Sister',
                'updated_at' => '2021-01-08 22:43:31',
            ),
        ));
        
        
    }
}