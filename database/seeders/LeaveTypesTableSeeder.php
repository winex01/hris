<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LeaveTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('leave_types')->delete();
        
        \DB::table('leave_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'SL - WP',
                'description' => 'Sick Leave With Pay',
                'with_pay' => 1,
                'created_at' => '2021-10-07 23:09:38',
                'updated_at' => '2021-10-07 23:20:34',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'SL - W/OP',
                'description' => 'Sick Leave Without Pay',
                'with_pay' => 0,
                'created_at' => '2021-10-07 23:19:54',
                'updated_at' => '2021-10-07 23:20:47',
            ),
        ));
        
        
    }
}