<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FamilyRelationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('family_relations')->delete();
        
        \DB::table('family_relations')->insert(array (
            0 => 
            array (
                'created_at' => '2021-01-05 04:13:12',
                'id' => 1,
                'name' => 'Emergency Contact',
                'updated_at' => '2021-01-05 04:13:12',
            ),
            1 => 
            array (
                'created_at' => '2021-01-05 04:13:18',
                'id' => 2,
                'name' => 'Father',
                'updated_at' => '2021-01-05 04:13:18',
            ),
            2 => 
            array (
                'created_at' => '2021-01-05 04:13:23',
                'id' => 3,
                'name' => 'Mother',
                'updated_at' => '2021-01-05 04:13:23',
            ),
            3 => 
            array (
                'created_at' => '2021-01-05 04:13:28',
                'id' => 4,
                'name' => 'Child',
                'updated_at' => '2021-01-05 04:13:28',
            ),
            4 => 
            array (
                'created_at' => '2021-01-05 04:13:35',
                'id' => 5,
                'name' => 'Spouse',
                'updated_at' => '2021-01-05 04:13:58',
            ),
        ));
        
        
    }
}