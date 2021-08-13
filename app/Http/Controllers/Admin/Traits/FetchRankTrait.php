<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchRankTrait
{
    public function fetchRank()
    {
        return $this->fetch(\App\Models\Rank::class);
    }
    
}