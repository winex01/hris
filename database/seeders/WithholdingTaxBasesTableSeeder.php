<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WithholdingTaxBasesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('withholding_tax_bases')->delete();
        
        \DB::table('withholding_tax_bases')->insert(array (
            0 => 
            array (
                'created_at' => '2021-08-20 20:16:19',
                'id' => 1,
                'name' => 'No Deduction',
                'updated_at' => '2021-08-20 20:16:19',
            ),
            1 => 
            array (
                'created_at' => '2021-08-20 20:16:31',
                'id' => 2,
                'name' => 'Daily',
                'updated_at' => '2021-08-20 20:16:31',
            ),
            2 => 
            array (
                'created_at' => '2021-08-20 20:16:40',
                'id' => 3,
                'name' => 'Weekly',
                'updated_at' => '2021-08-20 20:16:40',
            ),
            3 => 
            array (
                'created_at' => '2021-08-20 20:16:49',
                'id' => 4,
                'name' => 'Semi-Monthly',
                'updated_at' => '2021-08-20 20:16:49',
            ),
            4 => 
            array (
                'created_at' => '2021-08-20 20:16:56',
                'id' => 5,
                'name' => 'Monthly',
                'updated_at' => '2021-08-20 20:16:56',
            ),
        ));
        
        
    }
}