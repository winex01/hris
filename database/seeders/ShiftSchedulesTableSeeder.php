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
                'created_at' => '2021-02-16 15:20:17',
                'deleted_at' => NULL,
                'description' => 'Lunch break 12 to 1PM',
                'id' => 1,
                'name' => '08:30AM-05:30PM',
                'open_time' => 0,
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'updated_at' => '2021-02-16 15:46:18',
                'working_hours' => '{"working_hours": [{"end": "12:00", "start": "08:30"}, {"end": "17:30", "start": "13:00"}]}',
            ),
            1 => 
            array (
                'created_at' => '2021-02-16 15:35:58',
                'deleted_at' => NULL,
                'description' => 'Lunch break 1 to 2PM',
                'id' => 2,
                'name' => '10:30AM-07:30PM',
                'open_time' => 0,
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'updated_at' => '2021-02-16 15:38:32',
                'working_hours' => '{"working_hours": [{"end": "13:00", "start": "10:30"}, {"end": "19:30", "start": "14:00"}]}',
            ),
            2 => 
            array (
                'created_at' => '2021-02-16 15:40:03',
                'deleted_at' => NULL,
                'description' => NULL,
                'id' => 3,
                'name' => 'Open Time',
                'open_time' => 1,
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'updated_at' => '2021-02-16 15:40:03',
                'working_hours' => '{"working_hours": [{}]}',
            ),
            3 => 
            array (
                'created_at' => '2021-02-16 15:42:56',
                'deleted_at' => NULL,
                'description' => 'Lunch break  12 to 1PM',
                'id' => 4,
                'name' => '9:00AM-06:00PM',
                'open_time' => 0,
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'updated_at' => '2021-02-16 15:43:39',
                'working_hours' => '{"working_hours": [{"end": "12:00", "start": "21:00"}, {"end": "18:00", "start": "13:00"}]}',
            ),
            4 => 
            array (
                'created_at' => '2021-02-16 15:47:49',
                'deleted_at' => NULL,
                'description' => 'Lunch break is 12 to 1PM',
                'id' => 5,
                'name' => '08:00AM-05:00PM',
                'open_time' => 0,
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'updated_at' => '2021-02-16 15:47:49',
                'working_hours' => '{"working_hours": [{"end": "12:00", "start": "08:00"}, {"end": "17:00", "start": "13:00"}]}',
            ),
            5 => 
            array (
                'created_at' => '2021-02-16 15:51:53',
                'deleted_at' => NULL,
                'description' => NULL,
                'id' => 6,
                'name' => '06:00PM-02:00AM',
                'open_time' => 0,
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'updated_at' => '2021-02-16 15:51:53',
                'working_hours' => '{"working_hours": [{"end": "02:00", "start": "18:00"}]}',
            ),
            6 => 
            array (
                'created_at' => '2021-02-16 15:52:56',
                'deleted_at' => NULL,
                'description' => NULL,
                'id' => 7,
                'name' => '02:00PM-10:00PM',
                'open_time' => 0,
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'updated_at' => '2021-02-16 15:52:56',
                'working_hours' => '{"working_hours": [{"end": "22:00", "start": "14:00"}]}',
            ),
            7 => 
            array (
                'created_at' => '2021-02-16 15:56:36',
                'deleted_at' => NULL,
                'description' => 'Dynamic Break',
                'id' => 8,
                'name' => '08:00AM-05:00PM, DB',
                'open_time' => 0,
                'overtime_hours' => '{"overtime_hours": [{}]}',
                'updated_at' => '2021-02-16 15:56:36',
                'working_hours' => '{"working_hours": [{"end": "17:00", "start": "08:00"}]}',
            ),
        ));
        
        
    }
}