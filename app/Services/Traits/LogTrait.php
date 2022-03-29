<?php 

namespace App\Services\Traits;


trait LogTrait
{
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

        if (!$shiftToday->open_time) {
            // not open_time
            $logs = $this->employee->dtrLogs()
                ->with('dtrLogType')
                ->whereBetween('log', [$shiftToday->relative_day_start, $shiftToday->relative_day_end])
                ->whereIn('dtr_log_type_id', $logTypes);
        }else {
            // open_time
            $logs = $this->employee->dtrLogs()
            ->with('dtrLogType')
            ->whereDate('log', '=', $shiftToday->date)
            ->whereIn('dtr_log_type_id', $logTypes);
            
            //deduct 1 day to date and if not open_time, be sure to add whereNotBetween to avoid retrieving prev. logs.
            $prevShift = $this->shiftDetails(subDaysToDate($shiftToday->date));
            if ($prevShift && !$prevShift->open_time) {
                $logs = $logs->whereNotBetween('log', [$prevShift->relative_day_start, $prevShift->relative_day_end]);
            }

            // return compact('prevShift', 'this->shiftDetails', 'logs'); // NOTE:: for debug only
        }

        if ($logs) {
            return $logs->orderBy('log', $orderBy)->get();
        }

        return $logs;
    }
}