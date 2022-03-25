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

    public function getRegHourAttribute()
    {
        // IN - 1
        // OUT - 2
        // BREAK START - 3
        // BREAK END - 4
        
        $shift = $this->shiftSchedule;

        if (!$shift) {
            return;
        }

        // i requery using model instance, to improve performance rather than using $this->logs(super slow)
        $logs = modelInstance('DtrLog')
                ->select(['id', 'employee_id', 'log', 'dtr_log_type_id'])
                ->where('employee_id', $this->employee_id)
                ->whereDate('log', $this->date)
                ->get();
        
        $timeInCounts = $logs->where('dtr_log_type_id', 1)->count();
        $timeOutCounts = $logs->where('dtr_log_type_id', 2)->count();

        // if logs not complete / invalid
        if ($timeInCounts != $timeOutCounts) {
            return 'invalid';
        }
        

        // make logs by pairs, IN and OUT
        $entries = $logs->whereIn('dtr_log_type_id', [1,2])->sortBy('logs')->chunk(2);

        // if no dtr logs return null
        if (!$entries) {
            return;
        }


        $datas = [];
        
        // combine employee logs and employee working hours
        $i = 0;
        foreach ($entries as $temp) {
            $datas[$i++] = $temp->pluck('log', 'dtrLogType.name')->toArray();
        }
        

        $whs = $shift->working_hours_with_date;

        $i = 0;
        foreach ($whs as $wh) {
            $datas[$i]['whStart'] = $wh['start'];
            $datas[$i]['whEnd'] = $wh['end'];
            $i++;
        }
        // end combine employee logs and employee working hours


        $regHour = '00:00';


        // compute reg_hour
        foreach ($datas as $data) {
            $whStart = $data['whStart'];
            $whEnd = $data['whEnd'];
        
            $timeIn = carbonDateHourMinuteFormat($data['IN']);
            $timeOut = carbonDateHourMinuteFormat($data['OUT']);
            
            $regHourStart = $whStart; // default working hour start
            $regHourEnd = $whEnd; // default working hour end
        
            // if late, then make the timeIn as regHourStart
            if (carbonInstance($regHourStart)->lessThan($timeIn)) {
                $regHourStart = $timeIn;
            }
        
            // if undertime/early out, then make timeOut as regHourEnd
            if (carbonInstance($regHourEnd)->greaterThan($timeOut)) {
                $regHourEnd = $timeOut;
            }
        
            $regHourDiff = carbonInstance($regHourStart)->diff($regHourEnd)->format('%H:%I');
        
            $regHour = carbonAddHourTimeFormat($regHour, $regHourDiff);
        }
    
        
        // If dynamic break just get the break start and break end, then count total(duration) diff then deduct total_reg_hour.
        $breakDuration = $this->break_duration;
        if ($breakDuration != null) {
            $regHour = carbonSubHourTimeFormat($regHour, $breakDuration);
        }


        // hours per day is not null
        $hoursPerDay = $this->hours_per_day;
        if ($hoursPerDay) {
            $tempRegHour = currentDate().' '.$regHour;
            $tempHoursPerDay = currentDate().' '.carbonConvertIntToHourFormat($hoursPerDay);

            // if regHour is > than the hours per day (days per year) declared in emp info then override
            if  (carbonInstance($tempRegHour)->greaterThan($tempHoursPerDay)) {
                $regHour = carbonHourFormat($tempHoursPerDay);
            }
        }

        return $regHour;
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

    public function getBreakDurationAttribute()
    {
        if ($this->shiftSchedule->dynamic_break == true) {
            $breakStart = $this->logs->where('dtr_log_type_id', 3)->first();
            $breakEnd = $this->logs->where('dtr_log_type_id', 4)->first();

            // deduct regHour with break duration
            if ($breakStart && $breakEnd) {
                return carbonInstance($breakEnd->log)->diff($breakStart->log)->format('%H:%I');
            }
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
        if ($this->reg_hour == 'invalid') {
            return "<p title='Invalid Logs' class='text-danger font-weight-bold'>Invalid</p>";
        }
        
        return "<p title='hh:mm'>".$this->reg_hour."</p>";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
