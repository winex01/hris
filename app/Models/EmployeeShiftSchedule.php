<?php

namespace App\Models;

use App\Models\Model;

class EmployeeShiftSchedule extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employee_shift_schedules';
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
        static::addGlobalScope('currentEmployeeShiftScheduleScope', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->whereRaw('(
                    employee_shift_schedules.employee_id,
                    employee_shift_schedules.created_at
                ) = ANY(
                    SELECT 
                        t2.employee_id,
                        max(t2.created_at)
                    FROM employee_shift_schedules t2
                    WHERE t2.effectivity_date <= ?
                    GROUP BY t2.employee_id
            )', currentDate());
        });
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

    public function monday()
    {
        return $this->belongsTo(\App\Models\ShiftSchedule::class, 'monday_id');
    }

    public function tuesday()
    {
        return $this->belongsTo(\App\Models\ShiftSchedule::class, 'tuesday_id');
    }

    public function wednesday()
    {
        return $this->belongsTo(\App\Models\ShiftSchedule::class, 'wednesday_id');
    }

    public function thursday()
    {
        return $this->belongsTo(\App\Models\ShiftSchedule::class, 'thursday_id');
    }

    public function friday()
    {
        return $this->belongsTo(\App\Models\ShiftSchedule::class, 'friday_id');
    }

    public function saturday()
    {
        return $this->belongsTo(\App\Models\ShiftSchedule::class, 'saturday_id');
    }

    public function sunday()
    {
        return $this->belongsTo(\App\Models\ShiftSchedule::class, 'sunday_id');
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
