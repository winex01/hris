<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchEducationalLevelTrait
{
    public function fetchEducationalLevel()
    {
        return $this->fetch(\App\Models\EducationalLevel::class);
    }
    
}