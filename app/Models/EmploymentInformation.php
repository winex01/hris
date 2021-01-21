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
    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\CurrentEmploymentInfoScope);
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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOrderByField($query)
    {   
        // TODO:: create emp info field crud with reoder and pull out from there
        $orderByField = [
            'COMPANY', 
            'LOCATION', 
            'DEPARTMENT', 
            'DIVISION', 
            'SECTION', 
            'POSITION', 
            'LEVEL', 
            'RANK', 
            'BASIC_RATE',
            'BASIC_ADJUSTMENT',
            'DAYS_PER_YEAR', 
            'PAY_BASIS', 
            'PAYMENT_METHOD', 
            'EMPLOYMENT_STATUS', 
            'JOB_STATUS', 
            'GROUPING', 
        ];

        $sql = 'FIELD(field_name, "'.implode('","', $orderByField).'")';
        return $query->orderByRaw($sql);
    }

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
                // return number_format($value, config('hris.decimal_precision'));
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
