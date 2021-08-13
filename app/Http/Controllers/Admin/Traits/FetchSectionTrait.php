<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchSectionTrait
{
    public function fetchSection()
    {
        return $this->fetch(\App\Models\Section::class);
    }
    
}