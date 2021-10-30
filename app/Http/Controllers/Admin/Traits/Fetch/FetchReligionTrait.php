<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchReligionTrait
{
    public function fetchReligion()
    {
        return $this->fetch(\App\Models\Religion::class);
    }
    
}