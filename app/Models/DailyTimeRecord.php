<?php

namespace App\Models;

use App\Models\Model;

class DailyTimeRecord extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'daily_time_records';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
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
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class);
    }
    
    public function payrollPeriod()
    {
        return $this->belongsTo(\App\Models\PayrollPeriod::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getLeaveAttribute()
    {   
        // i use model instance to bypass this->employee and improve performance
        return modelInstance('LeaveApplication')
                ->where('employee_id', $this->employee_id)
                ->whereDate('date', $this->date)
                ->approved()
                ->first();
    }

    public function getRegHourAttribute()
    {
        // if no shift schedule return null
        // if no logs return null
        if (!$this->shift_schedule || !$this->logs) {
            return;
        } 

        // if logs not valid
        if (!$this->validateLogs()) {
            return 'invalid';
        }

        // default regHour is hours_per_day 
        $regHour = carbonConvertIntToHourFormat($this->hours_per_day);

        if (!$regHour) {
            return;
        }
        
        if ($this->deductions) {
            $regHour = carbonSubHourTimeFormat($regHour, $this->deductions);
        }

        return $regHour;
    }

    public function getDeductionsAttribute()
    {
        $deductions = '00:00';

        // deduct if has late
        if ($this->late) {
            $deductions = carbonAddHourTimeFormat($deductions, $this->late);
        }
        
        // deduct undertime/early out
        if ($this->undertime) {
            $deductions = carbonAddHourTimeFormat($deductions, $this->undertime);
        }

        // if has break excess then deduct
        if ($this->break_excess) {
            $deductions = carbonAddHourTimeFormat($deductions, $this->break_excess);
        }

        return $deductions;
    }

    // TODO:: wip, remove transfered to service class
    public function getLateAttribute()
    {
        // if no shift schedule return null
        // if no logs return null
        if (!$this->shift_schedule || !$this->logs) {
            return;
        } 

        // if logs not valid
        if (!$this->validateLogs()) {
            return 'invalid';
        }

        // get logs with type In = 1
        $logs = $this->logs->where('dtr_log_type_id', 1)->sortBy('logs');

        $workingHoursWithDate = $this->shift_schedule->working_hours_with_date;

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

        return $lateDuration;
    }

    public function getBreakExcessAttribute()
    {
        // if no shift schedule return null
        // if no logs return null
        if (!$this->shift_schedule || !$this->logs) {
            return;
        } 

        // if logs not valid
        if (!$this->validateLogs()) {
            return 'invalid';
        }

        if (!$this->shiftSchedule->dynamic_break) {
            return;
        }


        $dynamicBreakCredit = $this->shiftSchedule->dynamic_break_credit;
        $breakDuration = $this->break_duration;
        
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

    // TODO:: wip, remove, transfered in service class
    public function getUndertimeAttribute()
    {
        // if no shift schedule return null
        // if no logs return null
        if (!$this->shift_schedule || !$this->logs) {
            return;
        } 

        // if logs not valid
        if (!$this->validateLogs()) {
            return 'invalid';
        }

        // get logs with type Out = 2
        $logs = $this->logs->where('dtr_log_type_id', 2)->sortBy('logs');

        $workingHoursWithDate = $this->shift_schedule->working_hours_with_date;

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

    public function getBreakDurationAttribute()
    {
        // if no shift schedule return null
        // if no logs return null
        if (!$this->shift_schedule || !$this->logs) {
            return;
        } 

        // if logs not valid
        if (!$this->validateLogs()) {
            return 'invalid';
        }

        $shift = $this->shift_schedule;

        if (!$shift) {
            return;
        }

        $breakDuration = '00:00';

        if ($shift->dynamic_break == true) {
            $breakStart = $this->logs->where('dtr_log_type_id', 3)->first();
            $breakEnd = $this->logs->where('dtr_log_type_id', 4)->first();

            // deduct regHour with break duration
            if ($breakStart && $breakEnd) {
                $breakDuration = carbonTimeFormatDiff($breakEnd->log, $breakStart->log);
            }
        }

        return $breakDuration;
    }

    public function getHoursPerDayAttribute()
    {
        // current days per year (latest), to get hours per day
        $daysPerYearId = modelInstance('EmploymentInformation')
                            ->select('field_value')
                            ->where('employee_id', $this->employee_id)
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

    public function getLeaveListColumnAttribute()
    {
        $leave = $this->leave;
        
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

    public function getBreakExcessListColumnAttribute()
    {
        return $this->showHourMinuteTime($this->break_excess);
    }

    public function getRegHourListColumnAttribute()
    {
        return $this->showHourMinuteTime($this->reg_hour);
    }
    
    public function getLateListColumnAttribute()
    {
        return $this->showHourMinuteTime($this->late);
    }

    public function getUndertimeListColumnAttribute()
    {
        return $this->showHourMinuteTime($this->undertime);
    }

    public function getOvertimeListColumnAttribute()
    {
        return $this->showHourMinuteTime($this->overtime);
    }

    // TODO:: to be remove, transfered to service class
    private function showHourMinuteTime($attr)  
    {
        if ($attr == 'invalid') {
            return trans('lang.daily_time_records_details_row_invalid_logs');
        }
        
        return "<span title='".trans('lang.hour_minute_title_format')."'>".$attr."</span>";
    }

    // TODO:: wip, remove unused attributes here, instead use service class method
    // TODO:: wip, refactor employee and create service class
    // TODO:: create summary attribute
    // TODO:: overtime
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
