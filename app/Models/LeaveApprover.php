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
    public function applications()
    {
        return $this->hasMany(\App\Models\LeaveApplication::class, 'employee_id', 'employee_id');
    }

    public function approver()
    {
        return $this->belongsTo(\App\Models\Employee::class, 'approver_id');
    }

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
                leave_approvers.employee_id, 
                leave_approvers.level, 
                leave_approvers.created_at) = ANY(
                    SELECT 
                        t2.employee_id,
                        t2.level,
                        MAX(t2.created_at)
                    FROM leave_approvers t2
                    WHERE t2.effectivity_date <= ?
                    GROUP BY t2.employee_id, t2.level
            )', $date);
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
