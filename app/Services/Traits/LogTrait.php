<?php 

namespace App\Services\Traits;


trait LogTrait
{
    /**
     * @param  orderBy: asc / desc
     * @return collection
     * * NOTE:: Require shiftDetails and employee property instance
     */
    public function logs($logTypes = null, $orderBy = 'asc') 
    {
        // if no shift
        if (!$this->shiftDetails) {
            return;
        }
        
        $logs = null;
        
        if ($logTypes == null) {
            $logTypes = dtrLogTypes();
        }else { 
            if (!is_array($logTypes)) {
                $logTypes = (array) $logTypes;
            }
        }

        if (!$this->shiftDetails->open_time) {
            // not open_time
            $logs = $this->employee->dtrLogs()
                ->with('dtrLogType')
                ->whereBetween('log', [$this->shiftDetails->relative_day_start, $this->shiftDetails->relative_day_end])
                ->whereIn('dtr_log_type_id', $logTypes);
        }else {
            // open_time
            $logs = $this->employee->dtrLogs()
            ->with('dtrLogType')
            ->whereDate('log', '=', $this->shiftDetails->date)
            ->whereIn('dtr_log_type_id', $logTypes);
            
            //deduct 1 day to date and if not open_time, be sure to add whereNotBetween to avoid retrieving prev. logs.
            // TODO:: wip, test on open_time shift
            $prevShift = $this->shiftDetails(subDaysToDate($this->shiftDetails->date));
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