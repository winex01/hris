<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Builder;
class LeaveApprover extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'leave_approvers';
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
        static::addGlobalScope('CurrentLeaveApproverScope', function (Builder $builder) {
            (new self)->scopeDate($builder, currentDate());
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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeDate($query, $date)
    {
        return $query->withoutGlobalScope('CurrentLeaveApproverScope')
            ->whereRaw('(
                '.$this->table.'.employee_id, 
                '.$this->table.'.created_at) = ANY(
                    SELECT 
                        t2.employee_id,
                        MAX(t2.created_at)
                    FROM '.$this->table.' t2
                    WHERE t2.effectivity_date <= ?
                    GROUP BY t2.employee_id
            )', $date);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getApproversAttribute($value) 
    {
        return getApproversAttribute($this->attributes['approvers']);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
