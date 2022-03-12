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
                'id' => 1,
                'name' => 'No Deduction',
                'created_at' => '2021-08-20 20:16:19',
                'updated_at' => '2021-08-20 23:00:07',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Daily',
                'created_at' => '2021-08-20 20:16:31',
                'updated_at' => '2021-08-20 23:00:20',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Weekly',
                'created_at' => '2021-08-20 20:16:40',
                'updated_at' => '2021-08-20 23:00:28',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Semi-Monthly',
                'created_at' => '2021-08-20 20:16:49',
                'updated_at' => '2021-08-20 23:00:36',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Monthly',
                'created_at' => '2021-08-20 20:16:56',
                'updated_at' => '2021-08-20 23:00:44',
            ),
        ));
        
        
    }
}