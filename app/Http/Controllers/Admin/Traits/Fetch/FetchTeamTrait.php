<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchTeamTrait
{
    public function fetchTeam()
    {
        return $this->fetch(\App\Models\Team::class);
    }
    
}