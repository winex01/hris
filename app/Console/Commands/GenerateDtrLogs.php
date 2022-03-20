<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateDtrLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'winex:make-dtrlogs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate fake DTR Logs.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $employeeLists = employeeLists();
        $employeeChoice = $this->choice(
            'Which employee(s) to create DTR logs?',
            array_merge(['All'], $employeeLists),
            0,// $defaultIndex,
            null, //$maxAttempts = null,
            true//$allowMultipleSelections = false
        );

        $this->info("You've chosen:\n". implode("\n", $employeeChoice));
        
        $r = currentDate().'/'.currentDate();
        $dateRange = $this->ask('Enter DTR logs date range FROM and TO:', $r);

        $shiftChoice = $this->choice(
            'Select shift schedule to base DTR logs?',
            collect(shiftScheduleLists())->flatten()->toArray()
        );

        $randomTimeDiff = $this->ask('Enter random max number for php rand(0,n) function for early or late DTR logs:', 15);

        $this->info("Creating employee(s) DTR logs for date range {$dateRange} base on shift schedule {$shiftChoice}.");

        // if `All` is selected then disregard others and select all employees instead
        if (in_array("All", $employeeChoice)) {
            $employeeChoice = $employeeLists;
        }

        $shift = modelInstance('ShiftSchedule')->where('name', $shiftChoice)->firstOrFail();
        $dateRange = explode('/', $dateRange);

        $this->getOutput()->progressStart(count($employeeChoice));
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
                            'description' => 'php artisan winex:make-dtrlogs.'
                        ]);

                    }// end foreach $wh
                }
            }

            $this->getOutput()->progressAdvance();
        }

        $this->getOutput()->progressFinish();
    }
}
