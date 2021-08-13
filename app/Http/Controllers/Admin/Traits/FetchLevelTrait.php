<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchLevelTrait
{
    public function fetchLevel()
    {
        return $this->fetch(\App\Models\Level::class);
    }
    
}