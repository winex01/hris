<?php

namespace App\Models;

use App\Models\Model;

class LeaveApplication extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'leave_applications';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $revisionFormattedFields = [
        'status'      => 'options: 0.Pending|1.Approved|2.Denied',
    ];

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
    public function approvers()
    {
        return $this->hasMany(\App\Models\LeaveApprover::class, 'employee_id', 'employee_id');
    }
    
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(\App\Models\LeaveType::class);
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
    public function getStatusBadgeAttribute()
    {
        $temp = [
            0 => trans('lang.pending_badge'),
            1 => trans('lang.approved_badge'),
            2 => trans('lang.denied_badge'),
        ];

        return $temp[$this->status];
    }


    // public function getApproverListsAttribute()
    // {
    //     return $this->relations['approvers'];
    // }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
