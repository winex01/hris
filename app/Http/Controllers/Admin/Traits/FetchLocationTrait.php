<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchLocationTrait
{
    public function fetchLocation()
    {
        return $this->fetch(\App\Models\Location::class);
    }
    
}