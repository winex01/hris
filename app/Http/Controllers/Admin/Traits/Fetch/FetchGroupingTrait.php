<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchGroupingTrait
{
    public function fetchGrouping()
    {
        return $this->fetch(\App\Models\Grouping::class);
    }
}