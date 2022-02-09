<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchLocationTrait
{
    public function fetchLocation()
    {
        return $this->fetch(\App\Models\Location::class);
    }
    
}