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
        // if field_value is in json format
        if (isJson($value)) {
            $obj = json_decode($value);
            if (is_object($obj) && property_exists($obj, 'id')) {
                $class = convertToClassName(strtolower($this->field_name));

                switch ($this->field_name) {
                    case 'DAYS_PER_YEAR':
                        $temp = classInstance($class)->where('id', $obj->id)->first();
                        $fieldValue = $temp->days_per_year.' / '.$temp->days_per_week.' / '.$temp->hours_per_day;
                        break;
                    
                    default:
                        $fieldValue = classInstance($class)->where('id', $obj->id)->pluck('name')->first();
                        break;
                }

                return $fieldValue;                
            }
        }// end if isJson

        switch ($this->field_name) {
            case 'BASIC_ADJUSTMENT':
            case 'BASIC_RATE':
            return pesoCurrency($value);
                break;
        }

        return $value;
    }

    public function getFieldValueJsonAttribute()
    {
        return $this->attributes['field_value'];
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setFieldNameAttribute($value)
    {
        $this->attributes['field_name'] = strtoupper($value);
    }
}
