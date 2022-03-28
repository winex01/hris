<?php

namespace App\Services;

use App\Models\DailyTimeRecord;

class DailyTimeRecordService
{   
    use \App\Services\Traits\ShiftTrait;

    protected $dtr;

    protected $employee;

    protected $shiftDetails;

    protected $logs;

    public function __construct(DailyTimeRecord $dtr)
    {
        $this->dtr = $dtr;

        $this->employee = $this->dtr->employee;
        
        $this->shiftDetails = $this->shiftDetails($this->dtr->date); // * Trait
        
        $this->logs = $this->logs(); // * Trait
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getLeave()
    {
        return modelInstance('LeaveApplication')
                ->where('employee_id', $this->dtr->employee_id)
                ->whereDate('date', $this->dtr->date)
                ->approved()
                ->first();
        
    }

    public function getRegHour()
    {
        // if no shift schedule return null
        // if no logs return null
        if (!$this->shiftDetails || !$this->logs) {
            return;
        } 

        // if logs not valid
        if (!$this->validateLogs()) {
            return 'invalid';
        }

        // default regHour is hours_per_day 
        $regHour = carbonConvertIntToHourFormat($this->getHoursPerDay());

        if (!$regHour) {
            return;
        }
        
        if ($this->getTimeDeductions()) {
            $regHour = carbonSubHourTimeFormat($regHour, $this->getTimeDeductions());
        }

        return $regHour;
    }

    public function getHoursPerDay()
    {
        // current days per year (latest), to get hours per day
        $daysPerYearId = modelInstance('EmploymentInformation')
                            ->select('field_value')
                            ->where('employee_id', $this->dtr->employee_id)
                            ->daysPerYear()
                            ->first();
        
        if ($daysPerYearId) {
            $daysPerYearId = $daysPerYearId->field_value_id;
        }

        $hoursPerDay = modelInstance('DaysPerYear')->find($daysPerYearId);

        if ($hoursPerDay) {
            return (int)$hoursPerDay->hours_per_day;
        }

        return;
    }

    public function getTimeDeductions()
    {
        $deductions = '00:00';

        // deduct if has late
        if ($this->getLate()) {
            $deductions = carbonAddHourTimeFormat($deductions, $this->getLate());
        }
        
        // deduct undertime/early out
        if ($this->getUndertime()) {
            $deductions = carbonAddHourTimeFormat($deductions, $this->getUndertime());
        }

        return $deductions;
    }

    public function getBreakExcess()
    {
        // if no shift schedule return null
        // if no logs return null
        if (!$this->shiftDetails || !$this->logs) {
            return;
        } 

        // if logs not valid
        if (!$this->validateLogs()) {
            return 'invalid';
        }

        if (!$this->shiftDetails->dynamic_break) {
            return;
        }


        $dynamicBreakCredit = $this->shiftDetails->dynamic_break_credit;
        $breakDuration = $this->getBreakDuration();
        
        if (!$dynamicBreakCredit || !$breakDuration) {
            return;
        }
        
        $breakExcess = '00:00';

        // if employee breakDuration is greater than the dynamicBreakCredit define in shift then
        // get the excess time
        if (isCarbonTimeGreaterThan($breakDuration, $dynamicBreakCredit)) {
            $timeDiff = carbonTimeFormatDiff($breakDuration, $dynamicBreakCredit);
            $breakExcess = carbonAddHourTimeFormat($breakExcess, $timeDiff);
        }

        return $breakExcess;
    }

    public function getBreakDuration()
    {
        // if no shift schedule return null
        // if no logs return null
        if (!$this->shiftDetails || !$this->logs) {
            return;
        } 

        // if logs not valid
        if (!$this->validateLogs()) {
            return 'invalid';
        }

        $breakDuration = '00:00';

        if ($this->shiftDetails->dynamic_break == true) {
            $breakStart = $this->logs->where('dtr_log_type_id', 3)->first();
            $breakEnd = $this->logs->where('dtr_log_type_id', 4)->first();

            // deduct regHour with break duration
            if ($breakStart && $breakEnd) {
                $breakDuration = carbonTimeFormatDiff($breakEnd->log, $breakStart->log);
            }
        }

        return $breakDuration;
    }

    public function getUndertime()
    {
        // if no shift schedule return null
        // if no logs return null
        if (!$this->shiftDetails || !$this->logs) {
            return;
        } 

        // if logs not valid
        if (!$this->validateLogs()) {
            return 'invalid';
        }

        // get logs with type Out = 2
        $logs = $this->logs->where('dtr_log_type_id', 2)->sortBy('logs');

        $workingHoursWithDate = $this->shiftDetails->working_hours_with_date;

        $undertimeDuration = '00:00';

        $i = 0;
        foreach ($logs as $dtrLog) { // loop for OUT's
            $workingEnd = $workingHoursWithDate[$i]['end'];
            $timeOut = carbonDateHourMinuteFormat($dtrLog->log); 

            // if undertime, then add to undertimeDuration
            if (carbonInstance($timeOut)->lessThan($workingEnd)) {
                $undertime = carbonTimeFormatDiff($workingEnd, $timeOut);
                $undertimeDuration = carbonAddHourTimeFormat($undertimeDuration, $undertime);
            }

            $i++;
        }

        return $undertimeDuration;
    }

    public function getLate()
    {
        // if no shift schedule return null
        // if no logs return null
        if (!$this->shiftDetails || !$this->logs) {
            return;
        } 
        
        // if logs not valid
        if (!$this->validateLogs()) {
            return 'invalid';
        }
        
        // get logs with type In = 1
        $logs = $this->logs->where('dtr_log_type_id', 1)->sortBy('logs');
        
        $workingHoursWithDate = $this->shiftDetails->working_hours_with_date;

        $lateDuration = '00:00';

        $i = 0;
        
        foreach ($logs as $dtrLog) { // loop for IN's
            $workingStart = $workingHoursWithDate[$i]['start'];
            $timeIn = carbonDateHourMinuteFormat($dtrLog->log); 

            // if late, then add late to lateDuration
            if (carbonInstance($timeIn)->greaterThan($workingStart)) {
                $late = carbonTimeFormatDiff($workingStart, $timeIn);
                $lateDuration = carbonAddHourTimeFormat($lateDuration, $late);
            }

            $i++;
        }


        // if has break excess then add as late
        if ($this->getBreakExcess()) {
            $lateDuration = carbonAddHourTimeFormat($lateDuration, $this->getBreakExcess());
        }

        return $lateDuration;
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    /**
     * @param  orderBy: asc / desc
     * @return collection
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

    public function validateLogs()
    {
        $timeInCounts = $this->logs->where('dtr_log_type_id', 1)->count();
        $timeOutCounts = $this->logs->where('dtr_log_type_id', 2)->count();
    
        // if logs not complete then false
        if ($timeInCounts != $timeOutCounts) {
            return false;
        }

        return true; // success
    }

    /*
    |--------------------------------------------------------------------------
    | HTML Format
    |--------------------------------------------------------------------------
    */
    public function getDateHtmlFormat()
    {

        return '<span title="'.daysOfWeekFromDate($this->dtr->date).'">'.$this->dtr->date.'</span>';
    }

    public function getShiftScheduleHtmlFormat()
    {
        if ($this->shiftDetails != null) {
            $url = backpack_url('shiftschedules/'.$this->shiftDetails->id.'/show');
            return anchorNewTab($url, $this->shiftDetails->name, $this->shiftDetails->details_text);
        }

        return;
    }

    public function getLogsHtmlFormat()
    {
        $html = "";
        if ($this->logs) {
            foreach ($this->logs as $log) {
                $title = "";
                $url = backpack_url('dtrlogs/'.$log->id.'/show');
                $typeBadge = $log->dtrLogType->nameBadge;
                
                $title .= '<span class="'.config('appsettings.link_color').'" title="'.$log->log.'">'.$typeBadge.' '.carbonTimeFormat($log->log).'</span>';
                $title .= "<br>";
            
                $html .= anchorNewTab($url, $title);
            }
        }

        return $html;
    }

    public function getLateHtmlFormat()
    {
        return displayHourTimeInHtml($this->getLate());;
    }

    public function getUndertimeHtmlFormat()
    {
        return displayHourTimeInHtml($this->getUndertime());
    }

    public function getBreakExcessHtmlFormat()
    {
        return displayHourTimeInHtml($this->getBreakExcess());
    }

    public function getRegHourHtmlFormat()
    {
        return displayHourTimeInHtml($this->getRegHour());
    }

    public function getLeaveHtmlFormat()
    {
        $leave = $this->getLeave();
        
        // if has leave
        if ($leave) {
            $url = backpack_url('leaveapplication/'.$leave->id.'/show');
            $title = "Credit : $leave->credit_unit_name";
            $title .= "\n";
            $title .= "Desc : ".$leave->leaveType->description;

            return anchorNewTab(
                $url, 
                $leave->leaveType->name,
                $title
            );
        }

        return;
    }
}
// TODO:: test open time shift and check for bug
// TODO:: create summary attribute
// TODO:: overtime