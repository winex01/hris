<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchRelationTrait
{
    public function fetchRelation()
    {
        return $this->fetch(\App\Models\Relation::class);
    }
    
}