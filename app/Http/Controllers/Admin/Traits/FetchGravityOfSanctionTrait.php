<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchGravityOfSanctionTrait
{
    public function fetchGravityOfSanction()
    {
        return $this->fetch(\App\Models\GravityOfSanction::class);
    }
    
}