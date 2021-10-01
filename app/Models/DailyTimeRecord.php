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
        static::addGlobalScope('DailyTimeRecordScope', function (Builder $builder) {
            // TODO:: 
            $selectColumns = [
                'id',
                'last_name',
                'first_name',
                'middle_name',
                'badge_id',
            ];
            $builder->where('id', -1); // dont include the first table, only include the one in union at foreach
            $builder->select($selectColumns);
            $builder->selectRaw('"2021-01-01" as date');

            $payrolls = openPayrollPeriods();
            $firstEmployee = firstEmployee();

            foreach ($payrolls as $payroll) {
                $dates = carbonPeriodInstance($payroll->payroll_start, $payroll->payroll_end);
                foreach ($dates as $date) {
                    // echo $date->format('Y-m-d')."\n";
                    $date = $date->format('Y-m-d');
                    $builder->union(
                        // DB::table('employees')
                        modelInstance('Employee')
                        ->select($selectColumns)
                        ->selectRaw('"'.$date.'" as date')
                        ->where('id', $firstEmployee->id)
                        ->whereHas('employmentInformation', function ($q) use ($payroll) {
                            $q->grouping($payroll->grouping_id);
                        })
                    );
                }
            }

        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
