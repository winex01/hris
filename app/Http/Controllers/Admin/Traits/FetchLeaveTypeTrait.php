<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchLeaveTypeTrait
{
    public function fetchLeaveType()
    {
        return $this->fetch(\App\Models\LeaveType::class);
    }
}