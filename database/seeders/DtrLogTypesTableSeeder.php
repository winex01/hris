<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DtrLogTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('dtr_log_types')->delete();
        
        \DB::table('dtr_log_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'IN',
                'created_at' => '2021-04-10 11:41:18',
                'updated_at' => '2021-04-10 11:41:18',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'OUT',
                'created_at' => '2021-04-10 11:41:18',
                'updated_at' => '2021-04-10 11:41:18',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'BREAK START',
                'created_at' => '2021-04-10 11:41:47',
                'updated_at' => '2021-04-10 11:41:47',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'BREAK END',
                'created_at' => '2021-04-10 11:41:47',
                'updated_at' => '2021-04-10 11:41:47',
            ),
        ));
        
        
    }
}