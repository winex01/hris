<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchBloodTypeTrait
{
    public function fetchBloodType()
    {
        return $this->fetch(\App\Models\BloodType::class);
    }
    
}