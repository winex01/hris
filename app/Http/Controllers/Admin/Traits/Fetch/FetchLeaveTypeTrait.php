<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchLeaveTypeTrait
{
    public function fetchLeaveType()
    {
        return $this->fetch(\App\Models\LeaveType::class);
    }
}