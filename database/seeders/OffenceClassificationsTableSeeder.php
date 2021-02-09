<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OffenceClassificationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('offence_classifications')->delete();
        
        \DB::table('offence_classifications')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Attendance',
                'created_at' => '2021-02-09 21:24:20',
                'updated_at' => '2021-02-09 21:24:20',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Working Environment',
                'created_at' => '2021-02-09 21:26:28',
                'updated_at' => '2021-02-09 21:26:28',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Insubordination',
                'created_at' => '2021-02-09 21:26:33',
                'updated_at' => '2021-02-09 21:26:33',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Integrity',
                'created_at' => '2021-02-09 21:26:39',
                'updated_at' => '2021-02-09 21:26:39',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Others',
                'created_at' => '2021-02-09 21:26:43',
                'updated_at' => '2021-02-09 21:26:43',
            ),
        ));
        
        
    }
}