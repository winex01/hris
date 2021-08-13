<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchDivisionTrait
{
    public function fetchDivision()
    {
        return $this->fetch(\App\Models\Division::class);
    }
    
}