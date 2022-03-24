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
            $logs = $temp->pluck('log', 'dtrLogType.name')->toArray();
            $datas[$i++] = $logs;
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
    
        
        // TODO:: wip, TBD in break just get the break start and break end, then count total diff then deduct total_reg_hour.
        // TODO:: wip, if reg_hour is greater than days_per_year daily hour then assin it as reg_hour

        
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
            $hoursPerDay = (int)$hoursPerDay->hours_per_day;
        }

        // hours per day is not null
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
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
