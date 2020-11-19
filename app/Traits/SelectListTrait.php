<?php 

namespace App\Traits;

/**
 * use in backpack crud controller 
 */
trait SelectListTrait
{
	public function scopeSelectList($query)
    {
        return $query->pluck('name', 'id')->sortBy('name')->toArray();
    }
}