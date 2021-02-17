<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ShiftSchedulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('shift_schedules')->delete();
        
        \DB::table('shift_schedules')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '08:30AM-05:30PM',
                'open_time' => 0,
                'working_hours' => '{"working_hours": [{"end": "12:00", "start": "08:30"}, {"end": "17:30", "start": "13:00"}]}',
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'dynamic_break' => 0,
                'dynamic_break_credit' => NULL,
                'description' => 'Lunch break 12 to 1PM',
                'deleted_at' => NULL,
                'created_at' => '2021-02-16 15:20:17',
                'updated_at' => '2021-02-16 15:46:18',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '10:30AM-07:30PM',
                'open_time' => 0,
                'working_hours' => '{"working_hours": [{"end": "13:00", "start": "10:30"}, {"end": "19:30", "start": "14:00"}]}',
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'dynamic_break' => 0,
                'dynamic_break_credit' => NULL,
                'description' => 'Lunch break 1 to 2PM',
                'deleted_at' => NULL,
                'created_at' => '2021-02-16 15:35:58',
                'updated_at' => '2021-02-16 15:38:32',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Open Time',
                'open_time' => 1,
                'working_hours' => '{"working_hours": [{}]}',
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'dynamic_break' => 1,
                'dynamic_break_credit' => '01:00',
                'description' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2021-02-16 15:40:03',
                'updated_at' => '2021-02-17 06:27:56',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '9:00AM-06:00PM',
                'open_time' => 0,
                'working_hours' => '{"working_hours": [{"end": "12:00", "start": "21:00"}, {"end": "18:00", "start": "13:00"}]}',
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'dynamic_break' => 0,
                'dynamic_break_credit' => '01:00',
                'description' => 'Lunch break  12 to 1PM',
                'deleted_at' => NULL,
                'created_at' => '2021-02-16 15:42:56',
                'updated_at' => '2021-02-17 06:27:14',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => '08:00AM-05:00PM',
                'open_time' => 0,
                'working_hours' => '{"working_hours": [{"end": "12:00", "start": "08:00"}, {"end": "17:00", "start": "13:00"}]}',
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'dynamic_break' => 0,
                'dynamic_break_credit' => NULL,
                'description' => 'Lunch break is 12 to 1PM',
                'deleted_at' => NULL,
                'created_at' => '2021-02-16 15:47:49',
                'updated_at' => '2021-02-16 15:47:49',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => '06:00PM-02:00AM',
                'open_time' => 0,
                'working_hours' => '{"working_hours": [{"end": "02:00", "start": "18:00"}]}',
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'dynamic_break' => 0,
                'dynamic_break_credit' => NULL,
                'description' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2021-02-16 15:51:53',
                'updated_at' => '2021-02-16 15:51:53',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => '02:00PM-10:00PM',
                'open_time' => 0,
                'working_hours' => '{"working_hours": [{"end": "22:00", "start": "14:00"}]}',
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'dynamic_break' => 0,
                'dynamic_break_credit' => NULL,
                'description' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2021-02-16 15:52:56',
                'updated_at' => '2021-02-16 15:52:56',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => '08:00AM-05:00PM, DB',
                'open_time' => 0,
                'working_hours' => '{"working_hours": [{"end": "17:00", "start": "08:00"}]}',
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'dynamic_break' => 1,
                'dynamic_break_credit' => '01:00',
                'description' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2021-02-16 15:56:36',
                'updated_at' => '2021-02-17 11:12:48',
            ),
        ));
        
        
    }
}