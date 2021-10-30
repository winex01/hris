<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchShiftScheduleTrait
{
    public function fetchShiftSchedule()
    {
        return $this->fetch(\App\Models\ShiftSchedule::class);
    }
    
}