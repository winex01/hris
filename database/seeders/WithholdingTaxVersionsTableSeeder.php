<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WithholdingTaxVersionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('withholding_tax_versions')->delete();
        
        \DB::table('withholding_tax_versions')->insert(array (
            0 => 
            array (
                'active' => 1,
                'created_at' => '2021-08-17 00:40:33',
                'id' => 1,
            'name' => 'Revised Withholding Tax Table(version 2) - Effective January 1, 2018 to December 31, 2022',
                'updated_at' => '2021-08-21 05:36:42',
            ),
        ));
        
        
    }
}