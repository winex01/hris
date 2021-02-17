<?php

namespace App\Models;

use App\Models\Model;

class ShiftSchedule extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    
    public function __construct()
    {
        parent::__construct();

        $this->revisionFormattedFields = collect($this->revisionFormattedFields)->merge([
            'dynamic_break' => 'boolean:No|Yes',
            'open_time'     => 'boolean:No|Yes',
        ])->toArray();
    }

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
}
// TODO:: fix revision for fake fields less priority