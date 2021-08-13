<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchCivilStatusTrait
{
    public function fetchCivilStatus()
    {
        return $this->fetch(\App\Models\CivilStatus::class);
    }
    
}