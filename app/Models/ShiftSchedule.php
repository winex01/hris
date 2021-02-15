<?php

namespace App\Models;

use App\Models\Model;

class ShiftSchedule extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'shift_schedules';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $fakeColumns = [
        'working_hours',
        'overtime_hours',
    ];

    protected $casts = [
        'working_hours' => 'array',
        'overtime_hours' => 'array',
    ];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    private function jsonHoursText($arrayKey)
    {
        if ($this->open_time) {
            return;
        }

        $value = null;

        $data = array_key_exists($arrayKey, $this->{$arrayKey}) ? $this->{$arrayKey}[$arrayKey] : $this->{$arrayKey};
        foreach ($data as $wh) {
            if (!empty($wh)) {
                $value .= $wh['start'] .' - '.$wh['end']. '</br>';
            }
        }

        return $value;
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
    public function getWorkingHoursAsTextAttribute()
    {
        if ($this->open_time) {
            return trans('lang.shift_schedules_open_time');
        }

        return $this->jsonHoursText('working_hours');
    }

    public function getOvertimeHoursAsTextAttribute()
    {
        return $this->jsonHoursText('overtime_hours');
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
// TODO:: fix revision for fake fields less priority