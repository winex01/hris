<?php

namespace App\Models;

use App\Models\Model;

class DaysPerYear extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'days_per_years';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $appends = ['desc'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::addGlobalScope('orderByAsc', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->orderBy('days_per_year', 'ASC');
            $builder->orderBy('days_per_week', 'ASC');
            $builder->orderBy('hours_per_day', 'ASC');
        });
    }

    public function identifiableAttribute()
    {
        // process stuff here
        return 'desc';
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
    public function getDaysPerYearAttribute($value)
    {
        return number_format($value, config('hris.decimal_precision'));
    }

    public function getDaysPerWeekAttribute($value)
    {
        return number_format($value, config('hris.decimal_precision'));
    }

    public function getHoursPerDayAttribute($value)
    {
        return number_format($value, config('hris.decimal_precision'));
    }

    public function getDescAttribute()
    {
        return $this->days_per_year .' / '.$this->days_per_week.' / '.$this->hours_per_day;
    }    

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
