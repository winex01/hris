<?php 

namespace App\Traits;

/**
 * use in backpack crud controller 
 */
trait ScopeTrait
{
	public function scopeSelectLists($query)
    {
        return $query->pluck('name', 'id')->sortBy('name')->toArray();
    }
}