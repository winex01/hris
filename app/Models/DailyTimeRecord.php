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
        return $this->employee
                ->leaveApplications()
                ->where('date', $this->date)
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
        if (!$this->shiftSchedule) {
            return;
        }

        $timeInCounts = $this->logs->where('dtr_log_type_id', 1)->count();
        $timeOutCounts = $this->logs->where('dtr_log_type_id', 2)->count();

        // if logs not complete / invalid
        if ($timeInCounts != $timeOutCounts) {
            return 'invalid';
        }

        $whs = $this->shiftSchedule->working_hours_with_date;
        
        // make logs by pairs, IN and OUT
        $entries = modelInstance('DtrLog')
                    ->where('employee_id', $this->employee_id)
                    ->whereDate('log', $this->date)
                    ->whereIn('dtr_log_type_id', [1,2])
                    ->orderBy('log', 'asc')
                    ->get()
                    ->chunk(2);

        
        // if no dtr logs return null
        if (!$entries) {
            return;
        }

        
        $datas = [];
        
        // combine employee logs and employee working hours
        $i = 0;
        foreach ($entries as $temp) {
            // debug($temp);
            $logs = $temp->pluck('log', 'dtrLogType.name')->toArray();
            $datas[$i++] = $logs;
        }
        

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

        // TODO:: TBD in break just get the break start and break end, then count total diff then deduct total_reg_hour.
        // TODO:: get the current daily hour of employee using date in employment info
        // TODO:: if reg_hour is greater than days_per_year daily hour then assin it as reg_hour

        return $regHour;
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
