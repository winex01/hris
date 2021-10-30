<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchOffenceClassificationTrait
{
    public function fetchOffenceClassification()
    {
        return $this->fetch(\App\Models\OffenceClassification::class);
    }
    
}