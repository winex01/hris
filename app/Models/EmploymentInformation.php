<?php

namespace App\Models;

use App\Models\Model;

class EmploymentInformation extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employment_informations';
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
    public function getFieldValueAttribute($value)
    {
        if (isJson($value)) {
            $obj = json_decode($value);

            if (is_object($obj) && property_exists($obj, 'name')) {
                return $obj->name;
            }elseif ($this->field_name == 'DAYS_PER_YEAR') {
                return $obj->days_per_year.' / '.$obj->days_per_week.' / '.$obj->hours_per_day;
            }
        }

        switch ($this->field_name) {
            case 'BASIC_ADJUSTMENT':
            case 'BASIC_RATE':
            return trans('lang.currency').number_format($value, config('hris.decimal_precision'));
                break;
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setFieldNameAttribute($value)
    {
        $this->attributes['field_name'] = strtoupper(\Str::snake($value));
    }
}
