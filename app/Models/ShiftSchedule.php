<?php

namespace App\Models;

use App\Models\Model;

class ShiftSchedule extends Model
{

    protected $revisionFormattedFields = [
        'dynamic_break' => 'boolean:No|Yes',
        'open_time'     => 'boolean:No|Yes',
    ];

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
    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\OrderByNameScope);
    }
    
    private function jsonHoursText($arrayKey)
    {
        if ($this->open_time) {
            return;
        }

        $value = null;

        $data = array_key_exists($arrayKey, $this->{$arrayKey}) ? $this->{$arrayKey}[$arrayKey] : $this->{$arrayKey};
        foreach ($data as $wh) {
            if (!empty($wh)) {
                $value .= $wh['start'] .' - '.$wh['end']. '<br>';
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

    public function getDynamicBreakCreditAttribute($value)
    {
        return ($this->dynamic_break) ? $value : null;
    }

    public function getWorkingHoursAsExportAttribute()
    {
        $temp = str_replace('<br>', ", ", $this->working_hours_as_text);
        
        return rtrim($temp, ", ");
    }

    public function getOvertimeHoursAsExportAttribute()
    {
        $temp = str_replace('<br>', ", ", $this->overtime_hours_as_text);
    
        return rtrim($temp, ", ");
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setRelativeDayStartAttribute($value)
    {
        $this->attributes['relative_day_start'] = ($this->open_time) ? null : $value;
    }

    public function setDynamicBreakCreditAttribute($value)
    {
        $this->attributes['dynamic_break_credit'] = ($this->dynamic_break) ? $value : null;
    }
}
// TODO:: less priority - fix revision for fake fields 