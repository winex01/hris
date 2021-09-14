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
}
