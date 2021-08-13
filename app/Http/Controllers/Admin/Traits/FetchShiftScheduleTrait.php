<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchShiftScheduleTrait
{
    public function fetchShiftSchedule()
    {
        return $this->fetch(\App\Models\ShiftSchedule::class);
    }
    
}