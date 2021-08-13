<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchBloodTypeTrait
{
    public function fetchBloodType()
    {
        return $this->fetch(\App\Models\BloodType::class);
    }
    
}