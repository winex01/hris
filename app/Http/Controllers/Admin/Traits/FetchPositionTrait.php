<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchPositionTrait
{
    public function fetchPosition()
    {
        return $this->fetch(\App\Models\Position::class);
    }
}