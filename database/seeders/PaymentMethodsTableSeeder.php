<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_methods')->delete();
        
        \DB::table('payment_methods')->insert(array (
            0 => 
            array (
                'created_at' => '2021-01-11 10:01:31',
                'id' => 1,
                'name' => '-',
                'updated_at' => '2021-01-11 10:01:31',
            ),
            1 => 
            array (
                'created_at' => '2021-01-11 10:01:49',
                'id' => 2,
            'name' => 'Bank (ATM)',
                'updated_at' => '2021-01-11 10:01:49',
            ),
            2 => 
            array (
                'created_at' => '2021-01-11 10:01:54',
                'id' => 3,
                'name' => 'Cash',
                'updated_at' => '2021-01-11 10:01:54',
            ),
            3 => 
            array (
                'created_at' => '2021-01-11 10:01:58',
                'id' => 4,
                'name' => 'Check',
                'updated_at' => '2021-01-11 10:01:58',
            ),
        ));
        
        
    }
}