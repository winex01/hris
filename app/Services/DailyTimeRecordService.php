<?php

namespace App\Services;

use App\Models\DailyTimeRecord;

class DailyTimeRecordService
{   
    use \App\Services\Traits\ShiftTrait;
    use \App\Services\Traits\LogTrait;

    public $dtr;

    public $employee;

    public $shiftDetails;

    public $logs;

    public $hoursPerDay;

    public $validLogs;

    public $workedDuration;

    public $leave;

    public function __construct(DailyTimeRecord $dtr)
    {
        $this->dtr = $dtr;

        $this->employee = $this->dtr->employee;
        
        $this->shiftDetails = $this->shiftDetails($this->dtr->date); // * Trait
        
        $this->logs = $this->logs($this->dtr->date); // * Trait

        $this->hoursPerDay = $this->getHoursPerDay();
   
        $this->validLogs = $this->validateLogs();

        $this->workedDuration = $this->getWorkedDuration();

        $this->leave = $this->getLeave();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    
    /**
     * * NOTE:: this is the time length the employee worked
     */
    public function getWorkedDuration()
    {
         //* NOTE:: do not put !$this->shiftDetails return null, bec. if no shift then that means work is overtime. please check getOvertime method.

        // if no logs return null
        if (!$this->logs) {
            return;
        } 

        // if logs not valid
        if (!$this->validLogs) {
            return 'invalid';
        }

        $workedDuration = '00:00';

        // get all logs (IN, OUT, BREAK_START, BREAK_END)
        $logs = $this->logs->whereIn('dtr_log_type_id', [1,2,3,4])->sortBy('logs')->chunk(2);
        
        foreach ($logs as $dtrLogs) { 
            $start = null;
            $end = null;

            foreach ($dtrLogs as $dtrLog) {
                // Type IN and BREAK_OUT then assign as START
                if ($dtrLog->dtr_log_type_id == 1 || $dtrLog->dtr_log_type_id == 4) {
                    $start = $dtrLog->log;
                }elseif ($dtrLog->dtr_log_type_id == 3 || $dtrLog->dtr_log_type_id == 2) {
                    // Type BREAK_START and OUT then assign as END
                    $end = $dtrLog->log;
                }else {
                    // do nothing
                }
            }// end foreach $dtrLogs

            // if both is not null 
            if ($start && $end) {   
                $diff = carbonTimeFormatDiff($start, $end);
                $workedDuration = carbonAddHourTimeFormat($workedDuration, $diff);
            }   

        }// end foreach $logs

        return $workedDuration;
    }

    public function getLeave()
    {
        return modelInstance('LeaveApplication')
                ->where('employee_id', $this->dtr->employee_id)
                ->whereDate('date', $this->dtr->date)
                ->approved()
                ->first();
        
    }

    /**
     * * NOTE:: to verify if the value of regHour is correct
     * * (regHour + late + undertime + overtime) = workedDuration // TODO::
     */
    public function getRegHour()
    {
        // if no shift schedule return null
        // if no logs return null
        if (!$this->shiftDetails || !$this->logs) {
            return;
        } 

        // if logs not valid
        if (!$this->validLogs) {
            return 'invalid';
        }

        // default regHour is hours_per_day 
        $regHour = $this->hoursPerDay;

        if (!$regHour) {
            return;
        }
        
        // deduct if has late
        if ($this->getLate()) {
            $regHour = carbonSubHourTimeFormat($regHour, $this->getLate());
        }
        
        // deduct undertime/early out
        if ($this->getUndertime()) {
            $regHour = carbonSubHourTimeFormat($regHour, $this->getUndertime());
        }

        return $regHour;
    }

    // TODO:: wip, TBD or create test case for multiple overtime scope
    public function getOvertime()
    {   
        // if no logs return null
        if (!$this->logs) {
            return;
        }

        // if logs not valid
        if (!$this->validLogs) {
            return 'invalid';
        }

        $overtimeDuration = '00:00';

        if ($this->shiftDetails) {
            if ($this->shiftDetails->open_time) {
                $workedDuration = $this->workedDuration;
                $hoursPerDay = $this->hoursPerDay;
                
                // if open time and if working duration is greater than the hours per day then assign diff as overtime
                if ($workedDuration && $hoursPerDay) {
                    // use isCarbonTimeGreaterThan if comparing hh:mm format
                    if (isCarbonTimeGreaterThan($workedDuration, $hoursPerDay)) {
                        $diff = carbonTimeFormatDiff($workedDuration, $hoursPerDay);
                        $overtimeDuration = carbonAddHourTimeFormat($overtimeDuration, $diff);
                    }
                }
                
            }else { // not open time
                $lastTimeOut = $this->logs->where('dtr_log_type_id', 2)->last();
                $endWorkingAt = $this->shiftDetails->end_working_at;

                if ($lastTimeOut) {
                    $lastTimeOut = carbonDateHourMinuteFormat($lastTimeOut->log); // remove second
                    // endWorkingAt is already DateHourMinuteFormat so no need to remove second
                }
                
                if ($this->shiftDetails->overtime_hours_with_date == null) {
                    // if not open_time and the last time out is greater than end working hours take the diff
                    if ($lastTimeOut && $endWorkingAt) {
                        // use carbon->greaterThan if comparing date hour minute format ex. Y-m-d hh:mm
                        if (carbonInstance($lastTimeOut)->greaterThan($endWorkingAt)) {
                            $diff = carbonTimeFormatDiff($lastTimeOut, $endWorkingAt);
                            $overtimeDuration = carbonAddHourTimeFormat($overtimeDuration, $diff);
                        }
                    }
                }else { // if overtime_hours_with_date is not null
                    // TODO:: wip, fix this shit,. to accomodate multiple overtime hours scope
                    dump($this->shiftDetails->overtime_hours_with_date);
                    foreach ($this->shiftDetails->overtime_hours_with_date as $overTimeHourWithDate) {
                        $start = $overTimeHourWithDate['start'];
                        $end = $overTimeHourWithDate['end'];

                        // if lastTimeOut is in between a pair of overtime hours start and end / scope
                        if (carbonInstance($lastTimeOut)->betweenIncluded($start, $end)) {
                            $end = $lastTimeOut;

                            $diff = carbonTimeFormatDiff($start, $end);
                            $overtimeDuration = carbonAddHourTimeFormat($overtimeDuration, $diff);
                        }
                    }
                }// end if else overtime_hours_with_date
            }
            
        }else { 
            // if no shift and have logs then it's overtime
            if ($this->workedDuration) {
                $overtimeDuration = carbonAddHourTimeFormat($overtimeDuration, $this->workedDuration);
            }
        }
    
        return $overtimeDuration;
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
            return carbonConvertDecimalToHourFormat($hoursPerDay->hours_per_day);
        }

        return;
    }

    public function getBreakExcess()
    {
        // if no shift schedule return null
        // if no logs return null
        if (!$this->shiftDetails || !$this->logs) {
            return;
        } 

        // if logs not valid
        if (!$this->validLogs) {
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

    /** 
     * * NOTE:: break difference between break start and break end
     */
    public function getBreakDuration()
    {
        // if no shift schedule return null
        // if no logs return null
        if (!$this->shiftDetails || !$this->logs) {
            return;
        } 

        // if logs not valid
        if (!$this->validLogs) {
            return 'invalid';
        }

        $breakDuration = '00:00';

        if ($this->shiftDetails->dynamic_break == true) {
            $breakStart = $this->logs->where('dtr_log_type_id', 3)->first();
            $breakEnd = $this->logs->where('dtr_log_type_id', 4)->first();

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
        if (!$this->validLogs) {
            return 'invalid';
        }

        $undertimeDuration = '00:00';

        // if open time
        if ($this->shiftDetails->open_time) {
            // get hours per day and worked done
            $hoursPerDay = $this->hoursPerDay;
            $workedDuration = $this->workedDuration;

            // if worked duration(worked done) is less than hours per day, then diff. is under time
            if (isCarbonTimeLessThan($workedDuration, $hoursPerDay)) {
                $diff = carbonTimeFormatDiff($hoursPerDay, $workedDuration);
                $undertimeDuration = carbonAddHourTimeFormat($undertimeDuration, $diff);
            }

        }else { // else not open time
            // get logs with type Out = 2
            $logs = $this->logs->where('dtr_log_type_id', 2)->sortBy('logs');
    
            $workingHoursWithDate = $this->shiftDetails->working_hours_with_date;
    
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
        if (!$this->validLogs) {
            return 'invalid';
        }
        
        $lateDuration = '00:00';
        
        // if not open time        
        if (!$this->shiftDetails->open_time) {
            // get logs with type In = 1
            $logs = $this->logs->where('dtr_log_type_id', 1)->sortBy('logs');
            
            $workingHoursWithDate = $this->shiftDetails->working_hours_with_date;
    
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
        }

        // if has break excess then add as late regardless of it's open time or not
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

    public function getOvertimeHtmlFormat()
    {
        return displayHourTimeInHtml($this->getOvertime());
    }

    public function getLeaveHtmlFormat()
    {
        // if has leave
        if ($this->leave) {
            $url = backpack_url('leaveapplication/'.$this->leave->id.'/show');
            $title = "Credit : $this->leave->credit_unit_name";
            $title .= "\n";
            $title .= "Desc : ".$this->leave->leaveType->description;

            return anchorNewTab(
                $url, 
                $this->leave->leaveType->name,
                $title
            );
        }

        return;
    }
}
// TODO:: if leave is half day, regHorus default should be 4 hours. half of hoursPerDay define
// TODO:: do test case for days that have leave for half day.
// TODO:: TBD when DTR is save/process, should i save relationship in extra column as json?? or TBD retrieve employment info using/base processed_date timestamp 
// TODO:: TBD should i add Rest Day Overtime shift?? or TBD when DTR is process show notification for RestDay overtime.
// TODO:: TBD create bulk button to override/remove all overtime or assign it as 00:00, TBD bec. this can be prevented if
            // TODO:: overtime in shift schedule is not empty
// TODO:: TBD if no shift schedule and have logs, should i put Rest day overtime?
// TODO:: if no shift schedule and has logs then that means its Rest Day OT. put it in OVERTIME
// TODO:: regHour if open time hours_per_day should be default value, but the working duration
// TODO:: what if shift has dynamic break but didnt use break, what to do
// TODO:: test open time shift and check for bug
// TODO:: add night differential
// TODO:: fix preview / show operation
// TODO:: create summary attribute
// TODO:: verify (regHour + undertime + late + overtime + night diff) = workedDuration/workedDone
        // TODO:: TBD or add night diff as desciprtion title/tooltip in overtime when hover