<?php

namespace App\Services;


class EmployeeTimeClockService
{
    use \App\Services\Traits\ShiftTrait;

    protected $employee;

    public function __construct()
    {
        $this->employee = emp();
    }

    /**
     * @param  orderBy: asc / desc
     * @return collection
     */
    public function logs($date = null, $logTypes = null, $orderBy = 'asc') 
    {
        $logs = null;
        $date = ($date == null) ? currentDate() : $date;
        $shiftToday = $this->shiftDetails($date); 
        
        if ($logTypes == null) {
            $logTypes = dtrLogTypes();
        }else { 
            if (!is_array($logTypes)) {
                $logTypes = (array) $logTypes;
            }
        }

        if ($shiftToday) {
            if (!$shiftToday->open_time) {
                // !open_time
                $logs = $this->employee->dtrLogs()
                    ->whereBetween('log', [$shiftToday->relative_day_start, $shiftToday->relative_day_end])
                    ->whereIn('dtr_log_type_id', $logTypes);
            }else {
                // open_time
                $logs = $this->employee->dtrLogs()
                    ->whereDate('log', '=', $shiftToday->date)
                    ->whereIn('dtr_log_type_id', $logTypes);

                //deduct 1 day to date and if not open_time, be sure to add whereNotBetween to avoid retrieving prev. logs.
                $prevShift = $this->shiftDetails(subDaysToDate($shiftToday->date));
                if ($prevShift && !$prevShift->open_time) {
                    $logs = $logs->whereNotBetween('log', [$prevShift->relative_day_start, $prevShift->relative_day_end]);
                }

                // return compact('prevShift', 'shiftToday', 'logs'); // NOTE:: for debug only
            }
        }

        if ($logs) {
            return $logs->orderBy('log', $orderBy)->get();
        }

        return $logs;
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