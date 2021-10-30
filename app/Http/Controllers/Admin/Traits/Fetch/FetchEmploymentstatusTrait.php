<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchEmploymentstatusTrait
{
    public function fetchEmploymentstatus()
    {
        return $this->fetch(\App\Models\EmploymentStatus::class);
    }
    
}