<?php

namespace App\Models;

use DB;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;

class DailyTimeRecord extends Employee
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employees';
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
    protected static function booted()
    {
        static::addGlobalScope('CurrentDtrScope', function (Builder $builder) {
            (new self)->scopeEmployeeWithId($builder, firstEmployee()->id);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function employeeShiftSchedules()
    {
        return $this->hasMany(\App\Models\EmployeeShiftSchedule::class, 'employee_id');
    }    

    public function changeShiftSchedules()
    {
        return $this->hasMany(\App\Models\ChangeShiftSchedule::class, 'employee_id');
    }

    public function dtrLogs()
    {
        return $this->hasMany(\App\Models\DtrLog::class, 'employee_id');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeEmployeeWithId($query, $id)
    {
        $selectColumns = [
                'id',
                'last_name',
                'first_name',
                'middle_name',
                'badge_id',
            ];
        $query->where('id', -1); // dont include the first table, only include the one in union at foreach
        $query->select($selectColumns);
        $query->selectRaw('"2021-01-01" as date');

        $payrolls = openPayrollPeriods();
        foreach ($payrolls as $payroll) {
            $dates = carbonPeriodInstance($payroll->payroll_start, $payroll->payroll_end);
            foreach ($dates as $date) {
                // echo $date->format('Y-m-d')."\n";
                $date = $date->format('Y-m-d');
                $query->union(
                    // DB::table('employees')
                    modelInstance('Employee')
                    ->select($selectColumns)
                    ->selectRaw('"'.$date.'" as date')
                    ->where('id', $id)
                    ->whereHas('employmentInformation', function ($q) use ($payroll) {
                        $q->grouping($payroll->grouping_id);
                    })
                );
            }
        }

        return $query->withoutGlobalScope('CurrentDtrScope');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getShiftAttribute()
    {
        return $this->shiftDetails($this->date)->name ?? null;
    }

    public function getLogsAttribute()
    {
        $text = '';
        $logs = $this->logsOnDate($this->date);
        
        if ($logs) {
            foreach ($logs as $log) {
                // $text .= $log->dtrLogType->name.': '.carbonInstance($log->log)->format(config('appsettings.carbon_time_format')) .'<br>';
                // $text .= '<table class="table">';
                //     $text .= '<tr>';
                //         $text .= '<td>'.$log->dtrLogType->name.'</td>';
                //         $text .= '<td>'.carbonInstance($log->log)->format(config('appsettings.carbon_time_format')).'</td>';
                //     $text .= '</tr>';
                // $text .= '</table>';
                $text .= '<span title="'.$log->dtrLogType->name.'">'.carbonInstance($log->log)->format(config('appsettings.carbon_time_format')) .'</span>' .'<br>';
            }
        }

        return $text;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
