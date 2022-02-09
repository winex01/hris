<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchCivilStatusTrait
{
    public function fetchCivilStatus()
    {
        return $this->fetch(\App\Models\CivilStatus::class);
    }
    
}