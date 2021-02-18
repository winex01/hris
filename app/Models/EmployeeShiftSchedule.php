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
