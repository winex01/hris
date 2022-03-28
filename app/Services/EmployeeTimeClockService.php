<?php

namespace App\Services;


class EmployeeTimeClockService
{
    use \App\Services\Traits\ShiftTrait;
    use \App\Services\Traits\LogTrait;

    public $employee;

    public function __construct()
    {
        $this->employee = emp();
    }

    public function shiftToday()
    {
        $date = currentDate();
        $currentShift = $this->shiftDetails($date);
        $prevShift = $this->shiftDetails(subDaysToDate($date, 1));
        $currentDateTime = currentDateTime();

        //return compact('currentDateTime', 'currentShift', 'prevShift'); // NOTE:: comment this, for debug only

        // currentShift not open_time
        if ($currentShift && !$currentShift->open_time) {
            $dayStart = $currentShift->relative_day_start;
            $dayEnd = $currentShift->relative_day_end;
            if (carbonInstance($currentDateTime)->betweenIncluded($dayStart, $dayEnd)) {
                return $currentShift;
            }
        }

        // prevShift not open_time
        if ($prevShift && !$prevShift->open_time) {
            $dayStart = $prevShift->relative_day_start;
            $dayEnd = $prevShift->relative_day_end;
            if (carbonInstance($currentDateTime)->betweenIncluded($dayStart, $dayEnd)) {
                return $prevShift;
            }
        }

        if ($currentShift) {
            // currentShift open_time
            if ($currentShift->open_time) {
                return $currentShift;
            }

            // prevShift open_time
            if ($prevShift && $prevShift->open_time) {
                return $prevShift;
            }
        }

        return;
    }

    /**
     * show or hide Employee Time Clock buttons.
     * @return associative array
     */
    public function timeClock()
    {
        $in         = false;
        $out        = false;
        $breakStart = false;
        $breakEnd   = false;
        $hasShift   = false;

        $shiftToday = $this->shiftToday();
        
        if ($shiftToday) {
            $logsToday = $this->logs($shiftToday->date, [1,2]); // 1 = in, 2 = OUT
            $breaksToday = $this->logs($shiftToday->date, [3,4]); // 3 = break start , 4 = break end 
            $hasShift = true;

            // in
            if (($logsToday->last() == null) || $logsToday->last()->dtr_log_type_id == 2) {
                $in = true;
            }

            // out
            if ($logsToday->last() && $logsToday->last()->dtr_log_type_id == 1) {
                $out = true;
            }
            
            // break start
            if ($out && $shiftToday->dynamic_break && $breaksToday->last() == null) {
                $breakStart = true;
            }
            
            // break end
            if ($out && $breaksToday->last() && $breaksToday->last()->dtr_log_type_id == 3) {    
                $breakEnd = true;
                $out = false;
            }
           
            // logs in/out limit
            $outLimit = count($shiftToday->working_hours);
            $logOuts = $this->logs($shiftToday->date, [2]); // 2 = Out
            $totalOutLogs = ($logOuts != null) ? count($logOuts) : 0; 
            if ($totalOutLogs >= $outLimit) {
                $in = false;
            }
        }

        return [
            'hasShift'   => $hasShift,
            'in'         => $in,
            'out'        => $out,
            'breakStart' => $breakStart,
            'breakEnd'   => $breakEnd,
        ];
    }

}