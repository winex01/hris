<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class DtrLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employeeLists = employeeLists();
        $employeeChoice = $this->command->choice(
            'Which employee(s) to create DTR logs?',
            array_merge(['All'], $employeeLists),
            0,// $defaultIndex,
            null, //$maxAttempts = null,
            true//$allowMultipleSelections = false
        );

        $this->command->info("You've chosen:\n". implode("\n", $employeeChoice));
        
        $r = currentDate().'/'.currentDate();
        $dateRange = $this->command->ask('Enter DTR logs date range FROM and TO:', $r);

        $shiftChoice = $this->command->choice(
            'Select shift schedule to base DTR logs?',
            collect(shiftScheduleLists())->flatten()->toArray()
        );

        $randomTimeDiff = $this->command->ask('Enter random max number for php rand(0,n) function for early or late DTR logs:', 15);

        $this->command->info("Creating employee(s) DTR logs for date range {$dateRange} base on shift schedule {$shiftChoice}.");

        // if `All` is selected then disregard others and select all employees instead
        if (in_array("All", $employeeChoice)) {
            $employeeChoice = $employeeLists;
        }

        $shift = modelInstance('ShiftSchedule')->where('name', $shiftChoice)->firstOrFail();
        $startWorkingHours = $shift->start_working_hours;
        $endWorkingHours = $shift->end_working_hours;
        $dateRange = explode('/', $dateRange);

        // TODO::        
        $this->command->getOutput()->progressStart(count($employeeChoice));
        foreach ($employeeChoice as $employee) {
            // sleep(1);
            $employeeId = modelInstance('Employee')
                ->where('badge_id', getStringBetweenParenthesis($employee))
                ->pluck('id')
                ->first();

            $dates = carbonPeriodInstance($dateRange[0], $dateRange[1]); 
            foreach ($dates as $date) {
                $date = $date->format('Y-m-d');
                
                // loop shift working hourrs and insert logs
                foreach ($shift->working_hours['working_hours'] as $wh) {
                    foreach ($wh as $dtrLogType => $time) {
                        $log = null;
                        if (randomBoolean()){ // on time or early
                            $log = subMinutesToTime($time, rand(0, $randomTimeDiff)); 
                        }else { // late 
                            $log = addMinutesToTime($time, rand(0, $randomTimeDiff)); 
                        }

                        modelInstance('DtrLog')->create([
                            'employee_id' => $employeeId,
                            'log' => $date.' '.$log, // "2021-08-29 20:34:00",
                            'dtr_log_type_id' => ($dtrLogType == 'start') ? 1 : 2, // 1 = IN, 2 = OUT
                            'description' => 'System generated from seeder factory.'
                        ]);

                    }// end foreach $wh
                }
            }

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
    }
}
