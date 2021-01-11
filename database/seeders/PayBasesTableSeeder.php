<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PayBasesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('pay_bases')->delete();
        
        \DB::table('pay_bases')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Monthly Paid',
                'created_at' => '2021-01-12 05:06:33',
                'updated_at' => '2021-01-12 05:06:33',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Pro-rated Monthly Paid',
                'created_at' => '2021-01-12 05:07:41',
                'updated_at' => '2021-01-12 05:07:41',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Daily Paid',
                'created_at' => '2021-01-12 05:07:47',
                'updated_at' => '2021-01-12 05:07:47',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Hourly Paid',
                'created_at' => '2021-01-12 05:07:53',
                'updated_at' => '2021-01-12 05:07:53',
            ),
        ));
        
        
    }
}