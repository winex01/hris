<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchReligionTrait
{
    public function fetchReligion()
    {
        return $this->fetch(\App\Models\Religion::class);
    }
    
}