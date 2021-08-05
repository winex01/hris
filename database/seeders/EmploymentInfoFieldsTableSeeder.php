<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EmploymentInfoFieldsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('employment_info_fields')->delete();
        
        \DB::table('employment_info_fields')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'BASIC_RATE',
                'field_type' => 0,
                'parent_id' => NULL,
                'lft' => 20,
                'rgt' => 21,
                'depth' => 1,
                'created_at' => '2021-01-21 17:09:05',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'BASIC_ADJUSTMENT',
                'field_type' => 0,
                'parent_id' => NULL,
                'lft' => 22,
                'rgt' => 23,
                'depth' => 1,
                'created_at' => '2021-01-21 17:09:17',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'COMPANY',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 2,
                'rgt' => 3,
                'depth' => 1,
                'created_at' => '2021-01-21 17:09:37',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'DIVISION',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 8,
                'rgt' => 9,
                'depth' => 1,
                'created_at' => '2021-01-21 17:09:46',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'DEPARTMENT',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 6,
                'rgt' => 7,
                'depth' => 1,
                'created_at' => '2021-01-21 17:09:53',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'LOCATION',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 4,
                'rgt' => 5,
                'depth' => 1,
                'created_at' => '2021-01-21 17:10:01',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'DAYS_PER_YEAR',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 24,
                'rgt' => 25,
                'depth' => 1,
                'created_at' => '2021-01-21 17:10:08',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'EMPLOYMENT_STATUS',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 30,
                'rgt' => 31,
                'depth' => 1,
                'created_at' => '2021-01-21 17:10:17',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'GROUPING',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 34,
                'rgt' => 35,
                'depth' => 1,
                'created_at' => '2021-01-21 17:10:34',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'LEVEL',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 16,
                'rgt' => 17,
                'depth' => 1,
                'created_at' => '2021-01-21 17:10:42',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'PAY_BASIS',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 26,
                'rgt' => 27,
                'depth' => 1,
                'created_at' => '2021-01-21 17:10:54',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'PAYMENT_METHOD',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 28,
                'rgt' => 29,
                'depth' => 1,
                'created_at' => '2021-01-21 17:10:59',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'POSITION',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 14,
                'rgt' => 15,
                'depth' => 1,
                'created_at' => '2021-01-21 17:11:03',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'JOB_STATUS',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 32,
                'rgt' => 33,
                'depth' => 1,
                'created_at' => '2021-01-21 17:11:09',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'SECTION',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 12,
                'rgt' => 13,
                'depth' => 1,
                'created_at' => '2021-01-21 17:11:14',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'RANK',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 18,
                'rgt' => 19,
                'depth' => 1,
                'created_at' => '2021-01-21 17:11:33',
                'updated_at' => '2021-08-05 19:38:55',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'TEAM',
                'field_type' => 1,
                'parent_id' => NULL,
                'lft' => 10,
                'rgt' => 11,
                'depth' => 1,
                'created_at' => '2021-08-05 19:38:14',
                'updated_at' => '2021-08-05 19:38:55',
            ),
        ));
        
        
    }
}