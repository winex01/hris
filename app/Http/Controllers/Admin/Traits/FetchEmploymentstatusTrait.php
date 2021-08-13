<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchEmploymentstatusTrait
{
    public function fetchEmploymentstatus()
    {
        return $this->fetch(\App\Models\EmploymentStatus::class);
    }
    
}