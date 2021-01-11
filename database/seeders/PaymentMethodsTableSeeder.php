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
                'id' => 1,
                'name' => 'Cash',
                'created_at' => '2021-01-12 07:23:26',
                'updated_at' => '2021-01-12 07:23:26',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Check',
                'created_at' => '2021-01-12 07:23:33',
                'updated_at' => '2021-01-12 07:23:33',
            ),
            2 => 
            array (
                'id' => 3,
            'name' => 'Bank (ATM)',
                'created_at' => '2021-01-12 07:23:36',
                'updated_at' => '2021-01-12 07:23:36',
            ),
        ));
        
        
    }
}