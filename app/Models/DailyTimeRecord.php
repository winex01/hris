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

    public function getLogsAttribute()
    {   
        return $this->employee->logs($this->date);
    }

    public function getShiftScheduleAttribute()
    {
        return $this->employee->shiftDetails($this->date);
    }

    /**
     *
     * Note:: working_duration attribute is reg_hour without any deductions 
     * such as: late, break, undertime and etc.
     * 
     */
    public function getWorkingDurationAttribute()
    {
        if (!$this->shift_schedule) {
            return;
        }
        
        // i requery using model instance, to improve performance rather than using $this->logs(super slow)
        $logs = modelInstance('DtrLog')
                ->select(['id', 'employee_id', 'log', 'dtr_log_type_id'])
                ->where('employee_id', $this->employee_id)
                ->whereDate('log', $this->date)
                ->get();
        
        // if logs not valid
        if (!$this->validateLogs()) {
            return 'invalid';
        }

        // make logs by pairs, IN and OUT
        $entries = $logs->whereIn('dtr_log_type_id', [1,2])->sortBy('logs')->chunk(2);

        // if no dtr logs return null
        if (!$entries) {
            return;
        }
        
        $workingDuration = '00:00';

        // compute reg_hour
        foreach ($entries as $data) {
            $data = $data->pluck('log', 'dtr_log_type_id')->toArray();

            $workingDurationStart = carbonDateHourMinuteFormat($data[1]); // IN
            $workingDurationEnd = carbonDateHourMinuteFormat($data[2]); // OUT
            
            $workingDurationDiff = carbonInstance($workingDurationStart)->diff($workingDurationEnd)->format('%H:%I');
        
            $workingDuration = carbonAddHourTimeFormat($workingDuration, $workingDurationDiff);
        }
    
        return $workingDuration;
    }

    public function getRegHourAttribute()
    {
        $regHour = $this->working_duration;
    
        if (!$regHour) {
            return;
        }

        // if validation logs is fail return
        if  ($regHour == 'invalid') {
            return $regHour;
        }

        // TODO:: TBD  here or in worked_duration 
        //        if worked done is greater than the emp's hours_per_day then override it.
        // hours per day is not null
        // $hoursPerDay = $this->hours_per_day;
        // if ($hoursPerDay) {
        //     $tempRegHour = currentDate().' '.$regHour;
        //     $tempHoursPerDay = currentDate().' '.carbonConvertIntToHourFormat($hoursPerDay);

        //     // if regHour is > than the hours per day (days per year) declared in emp info then override
        //     if  (carbonInstance($tempRegHour)->greaterThan($tempHoursPerDay)) {
        //         $regHour = carbonHourFormat($tempHoursPerDay);
        //     }
        // }

        // if has dynamic break, then deduct break
        $breakDuration = $this->break;
        if ($breakDuration) {
            $regHour = carbonSubHourTimeFormat($regHour, $breakDuration);
        }
        
        // deduct late
        $lateDuration = $this->late;
        if ($lateDuration) {
            $regHour = carbonSubHourTimeFormat($regHour, $lateDuration);
        }
        

        return $regHour;

            
        // TODO:: undertime
        // // if undertime/early out, then make timeOut as regHourEnd
        // if (carbonInstance($regHourEnd)->greaterThan($timeOut)) {
        //     $regHourEnd = $timeOut;
        // }
    }

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

        // make logs by pairs, IN and OUT
        $logs = $this->logs->where('dtr_log_type_id', 1)->sortBy('logs');

        $workingHoursWithDate = $this->shift_schedule->working_hours_with_date;

        $lateDuration = '00:00';

        $i = 0;
        foreach ($logs as $dtrLog) { // loop for IN's
            $workingStart = $workingHoursWithDate[$i]['start'];
            $timeIn = carbonDateHourMinuteFormat($dtrLog->log); 

            // if late, then add late to lateDuration
            if (carbonInstance($timeIn)->greaterThan($workingStart)) {
                $late = carbonInstance($workingStart)->diff($timeIn)->format('%H:%I');
                $lateDuration = carbonAddHourTimeFormat($lateDuration, $late);
            }

            $i++;
        }

        return $lateDuration;
    }

    public function getBreakAttribute()
    {
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
                $breakDuration = carbonInstance($breakEnd->log)->diff($breakStart->log)->format('%H:%I');
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

    // Columns in Lists Attribute
    public function getShiftScheduleListColumnAttribute()
    {
        $shift = $this->shift_schedule; 

        if ($shift != null) {
            $url = backpack_url('shiftschedules/'.$shift->id.'/show');
            return anchorNewTab($url, $shift->name, $shift->details_text);
        }

        return;
    }

    public function getLogsListColumnAttribute()
    {
        $logs = $this->logs;
        $html = "";
        if ($logs) {
            foreach ($logs as $log) {
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

    private function showHourMinuteTime($attr)  
    {
        if ($attr == 'invalid') {
            return trans('lang.daily_time_records_details_row_invalid_logs');
        }
        
        return "<span title='".trans('lang.hour_minute_title_format')."'>".$attr."</span>";
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
