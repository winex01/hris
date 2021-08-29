<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Model;

class DtrLog extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'dtr_logs';
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
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        $temp =  openPayrollDetails();
        static::addGlobalScope('CurrentDtrLogsScope', function (Builder $builder) use($temp) {
            $builder->whereBetween('log', [$temp->date_start, $temp->date_end]);
            $builder->whereIn('employee_id', $temp->employee_ids);
        });
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

    public function dtrLogType()
    {
        return $this->belongsTo(\App\Models\DtrLogType::class);
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
