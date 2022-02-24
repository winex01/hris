<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Collection;

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
    public function scopeApproved($query)
    {
        return $query->where('status', 1);
    }

    public function scopeDenied($query)
    {
        return $query->where('status', 2);
    }

    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getApproversAttribute($value) 
    {
        $approvers = json_decode($this->attributes['approvers'], true);
        
        debug($approvers);

        $approvers = collect($approvers)->mapWithKeys(function ($item, $key) {
            $employee = modelInstance('Employee')->findOrFail($item['employee_id']);

            return [
                $key => [
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                ]
            ];
        })->toArray();

        // debug($approvers);
        // debug($value);
        return json_encode($approvers);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
