<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchJobstatusTrait
{
    public function fetchJobstatus()
    {
        return $this->fetch(\App\Models\JobStatus::class);
    }
    
}