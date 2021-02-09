<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GravityOfSanctionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('gravity_of_sanctions')->delete();
        
        \DB::table('gravity_of_sanctions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '1st Offence',
                'created_at' => '2021-02-09 18:56:04',
                'updated_at' => '2021-02-09 18:59:41',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '2nd Offence',
                'created_at' => '2021-02-09 18:56:21',
                'updated_at' => '2021-02-09 18:56:21',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '3rd Offence',
                'created_at' => '2021-02-09 18:56:36',
                'updated_at' => '2021-02-09 18:56:36',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '4th Offence',
                'created_at' => '2021-02-09 18:56:41',
                'updated_at' => '2021-02-09 18:56:41',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => '5th Offence',
                'created_at' => '2021-02-09 18:56:47',
                'updated_at' => '2021-02-09 18:56:47',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Dismissal',
                'created_at' => '2021-02-09 18:56:58',
                'updated_at' => '2021-02-09 18:56:58',
            ),
        ));
        
        
    }
}