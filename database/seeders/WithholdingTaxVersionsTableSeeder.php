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
                'id' => 1,
            'name' => 'Revised Withholding Tax Table(version 2) - Effective January 1, 2018 to December 31, 2022',
                'created_at' => '2021-08-17 00:40:33',
                'updated_at' => '2021-08-17 00:40:33',
            ),
        ));
        
        
    }
}