<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchCitizenshipTrait
{
    public function fetchCitizenship()
    {
        return $this->fetch(\App\Models\Citizenship::class);
    }
    
}