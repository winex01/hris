<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchGroupingTrait
{
    public function fetchGrouping()
    {
        return $this->fetch(\App\Models\Grouping::class);
    }
}