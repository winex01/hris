<?php

namespace App\Models;

use App\Models\Model;

class PayrollPeriod extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'payroll_periods';
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
    public function close()
    {
        $this->attributes['status'] = 0;
        return $this;
    }

    public function open()
    {
        $this->attributes['status'] = 1;
        return $this;
    }

    // NOTE: use this method to add conditional to buttons
    // if it's in array it will show to the list rows if not it 
    // will be hidden,
    // NOTE:: if you use this functionalities in other models,
    // dont forget to override the edit & update in CRUD controller
    public function showTheseLineButtons()
    {
        if ($this->attributes['status'] == 0) {
            // return only
            return [
                'openOrClosePayroll',
                'show',
            ];
        }

        return [
            'openOrClosePayroll',
            'show',
            'update',
            'delete',
            'forceDelete',
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function grouping()
    {
        return $this->belongsTo(\App\Models\Grouping::class);
    }

    public function withholdingTaxBasis()
    {
        return $this->belongsTo(\App\Models\WithholdingTaxBasis::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOpen($query)
    {
        return $query->where('status', 1);
    }

    public function scopeClose($query)
    {
        return $query->where('status', 0);
    }
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
    // i cast it since date_range field in backpack is in timestamp form so revision not affected
    public function setPayrollStartAttribute($value) {
        $this->attributes['payroll_start'] = carbonTimestampToDate($value);
    }

    // i cast it since date_range field in backpack is in timestamp form so revision not affected
    public function setPayrollEndAttribute($value) {
        $this->attributes['payroll_end'] = carbonTimestampToDate($value);
    }
}
