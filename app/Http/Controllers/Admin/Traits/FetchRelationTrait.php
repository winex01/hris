<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchRelationTrait
{
    public function fetchRelation()
    {
        return $this->fetch(\App\Models\Relation::class);
    }
    
}