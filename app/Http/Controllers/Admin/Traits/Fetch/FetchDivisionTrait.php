<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchDivisionTrait
{
    public function fetchDivision()
    {
        return $this->fetch(\App\Models\Division::class);
    }
    
}